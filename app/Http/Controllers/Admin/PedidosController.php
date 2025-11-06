<?php

namespace App\Http\Controllers\Admin;

use App\Models\BaseStatusPedidos;
use App\Models\EntregaPedido;
use App\Models\ItensPedidos;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Estoque;
use App\Models\Franquias;
use App\Models\Pedidos;
use App\Models\Periodos;
use App\Models\PeriodosValores;
use App\Models\Produtos;
use App\Models\Promocoes;
use App\Models\RegiaoAtendida;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class PedidosController extends Controller
{

    public function delete($id)
    {
        $pedido = Pedidos::find($id);
        
        if (!$pedido) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pedido não encontrado'
            ], 404);
        }
    
        $itens_pedido = ItensPedidos::where('id_pedido', $pedido->id)->get(); 
        
        foreach($itens_pedido as $item) {
            $item->delete();
        }
        
        $pedido->delete();
    
        return response()->json([
            'status' => 'ok',
            'message' => 'Pedido deletado com sucesso.'
        ]);
    }

    public function buscaCpf(Request $request)
    {    
        $cpf = $request->input('cpf');
        
        $usuario = User::whereHas('dados', function($query) use ($cpf) {
            $query->where('cpf', $cpf);
        })->with('dados')->first();
    
        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Nenhum usuário encontrado com este CPF'
            ], 404);
        }
    
        return response()->json([
            'success' => true,
            'usuario' => $usuario,
        ]);
    }

    public function recarregarClientes()
    {
        if(Auth::user()->role == 'admin' || Auth::user()->role == 'master')
        {
            $clientes= User::where('role', 'user')->get();
        }
        elseif(Auth::user()->role == 'franqueado')
        {              
            $clientes_ids = Pedidos::where('id_franquia', Auth::user()->id_franquia)->pluck('id_cliente');
            $clientes = User::whereIn('id', $clientes_ids)->where('role', 'user')->get();
        }

        return response()->json([
            'success' => true,
            'usuarios' => $clientes->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                ];
            })
        ]);
    }

    public function index(Request $request)
    {
        $data = $request->all();
        $franquia = $request->input('franquia'); 

        $query = Pedidos::query();

        if (Auth::user()->role == 'franqueado') {
            $query->where('id_franquia', Auth::user()->id_franquia);
        }

        if ($request->filled('franquia') && $franquia != 'todas') {

            $query->whereHas('franquia', function ($q) use ($request) {
                $q->where('tipo_franqueado', $request->input('franquia'));
            });
        }
        
        if ($request->has('campo') && $request->has('termo')) {

            $campo = $request->input('campo');
            $termo = $request->input('termo');

            if ($campo == 'cliente') {
                $query->whereHas('cliente', function ($q) use ($termo) {
                    $q->where('name', 'LIKE', "%$termo%");
                });
            } 
            elseif ($campo == 'numero') {
                $query->where('numero', 'LIKE', "%$termo%");
            } 
        }
        
        $pedidos = $query->orderBy('created_at', 'desc')->paginate(10);
        $base = BaseStatusPedidos::where('status', 'ativo')->get();

        if ($request->ajax()) {
            
            return view('admin.pedidos._list-pedidos', compact('pedidos', 'base'))->render();
        }

        return view('admin.pedidos.index', compact('pedidos', 'base'));
    }


    public function new()
    {   
        $user_franquia_id = Auth::user()->id_franquia;
        $tipo_franqueado = Auth::user()->franquia->tipo_franqueado ?? null; 

        if(Auth::user()->role == 'admin' || Auth::user()->role == 'master')
        {
            $clientes= User::where('role', 'user')->orderBy('name', 'ASC')->get();
        }
        elseif(Auth::user()->role == 'franqueado')
        {              
            $clientes_ids = Pedidos::where('id_franquia', Auth::user()->id_franquia)->pluck('id_cliente');
            $clientes = User::whereIn('id', $clientes_ids)->where('role', 'user')->orderBy('name', 'ASC')->get();
        }
        
        $franquias = Franquias::where('status', 'ativo')->select()->distinct()->get();
        $franquiasRetirada =  Franquias::where('retirada_balcao', 'sim')->select()->distinct()->get();
        
        $urlFacilitTrip = 'https://facilitrip.com.br';
        $urlFacilitToy = 'https://facilitoy.facilitrip.com.br';

        return view('admin.pedidos.new', compact('franquias', 'franquiasRetirada', 'clientes', 'urlFacilitTrip', 'urlFacilitToy'));
    }

    public function pesquisarFranquia(Request $request)
    {
        $franquia = $request->input('franquia');
        $bairro = $request->input('bairro');

        if ($bairro) {
            $bairro = explode(",", $bairro);
            $bairro = '%' . $bairro[0] . '%';
            $regiao = RegiaoAtendida::where('bairro', 'like', $bairro)->first();
            $franquia = Franquias::find($regiao->id_franqueado);
        } else {
            $franquia = Franquias::find($franquia);
        }
        return response()->json($franquia);
    }
    public function produtosDisponivel(Request $request)
    {
        $cont = $request->cont;
        $franquiaId = intval($request->franquia);
        $franquia = Franquias::find($franquiaId);

        if (!$franquia) {
            return response()->json(['error' => 'Franquia não encontrada'], 404);
        }

        $produtos = Produtos::where('produto_catalogo', 'nao')->where('status', 'ativo')
            ->where('tipo', $franquia->tipo_franqueado);

        if ($franquia->tipo_franqueado === 'trip') {
            $produtos = $produtos->orderBy('nome', 'ASC')->get();
            $startDate = Carbon::parse($request->data_inicio);
            $endDate = Carbon::parse($request->data_termino);

            $availableProducts = $this->calcularDisponibilidade($produtos, $franquiaId, $startDate, $endDate, 'aluguel');

        } elseif ($franquia->tipo_franqueado === 'toy') {
            $produtos = $produtos->where('id_franquia', $franquiaId)->orderBy('nome', 'ASC')->get();
            $modalidade = $request->modalidade;
            $startDate = Carbon::parse($request->data_inicio);
            $endDate = Carbon::parse($request->data_termino);

            $availableProducts = $this->calcularDisponibilidade($produtos, $franquiaId, $startDate, $endDate, $modalidade);
        } else {
            $availableProducts = [];
        }


        return view('admin.pedidos._lisProdutos', compact('produtos', 'availableProducts', 'cont'));
    }

    private function calcularDisponibilidade($produtos, $franquiaId, $startDate, $endDate, $modalidade = null)
    {
        $availableProducts = [];

        foreach ($produtos as $produto) {
            if ($modalidade == 'aluguel'){
                $estoque = Estoque::where('status', 'Disponível')
                ->where('id_franqueado', $franquiaId)
                ->where('id_produto', $produto->id)
                ->where('tipo_locacao', '!=', 'venda');

                $itensIndisponiveis = $this->verificarDisponibilidade($franquiaId, $produto->id, $startDate, $endDate);
            }
            else if ($modalidade == 'venda'){
                $estoque = Estoque::where('status', 'Disponível')
                ->where('id_franqueado', $franquiaId)
                ->where('id_produto', $produto->id)
                ->where('tipo_locacao', '!=', 'aluguel');

                $itensIndisponiveis = ItensPedidos::where('id_produto', $produto->id)
                ->where('tipo_locacao', 'venda')
                ->whereHas('pedido', function ($subquery) {
                    $subquery->where('id_franquia', localizacao()->franquia_id);
                    $subquery->where('id_status', '>=', 3);
                    $subquery->where('id_status', '!=', 10);
                    $subquery->where('id_status', '!=', 7);
                })
                ->count();
            }

            $estoque = $estoque->count();

            if ($estoque > $itensIndisponiveis) {
                $availableProducts[] = [
                    'produto_id' => $produto->id,
                    'estoque_disponivel' => $estoque - $itensIndisponiveis,
                ];
            }
        }

        return $availableProducts;
    }


    public function valorPeriodo($id, $id_produto, $modalidade)
    {
        if ($id_produto == '0'){
            $periodo_valor = PeriodosValores::first();
        }
        else{
            if($modalidade == 'aluguel'){
                $periodo_valor = PeriodosValores::where('id_produto', $id_produto)->where('id_periodo', $id)->first();
            }
            else{
                $periodo_valor = PeriodosValores::where('id_produto', $id_produto)->where('id_periodo', $id)->first();
                $produto = Produtos::find($id_produto);
                $periodo_valor->valor_periodo = $produto->valor_base_diaria;
            }
            
        }
        
        $periodo = Periodos::find($id);

        return response()->json([
            'periodo_valor' => $periodo_valor,
            'periodo' => $periodo
        ]);
    }

    // public function qtdMaxProduto(Request $request)
    // {
    //     $produtoId = $request->input('id');
    //     $franquiaId = $request->input('franquia');
    //     $produto = Produtos::find($produtoId);

    //     $franquia = Franquias::find(intval($request->franquia));

    //     if ($franquia->tipo_franqueado == 'trip'){
    //         $startDate = Carbon::parse($request->data_inicio);
    //         $endDate = Carbon::parse($request->data_termino);
    //         $estoque = 0;
    //         $itens_indisponiveis = 0;

    //         $estoque = Estoque::where('status', 'Disponível')
    //             ->where('id_franqueado', $franquiaId)
    //             ->where('id_produto', $produto->id)
    //             ->count();

    //         $produtoId = $produto->id;

    //         $startDate = Carbon::parse($request->input('data_incio'));
    //         $endDate = Carbon::parse($request->input('data_fim'));

    //         $itens_indisponiveis =  ItensPedidos::where('id_produto', $produtoId)
    //         ->whereHas('entrega', function ($query) use ($startDate, $endDate) {
    //                $query->where('data_devolucao','>' ,$startDate->format('Y-m-d'));
    //            })
    //         ->whereHas('pedido', function ($subquery) {
    //                 $subquery->where('id_franquia', localizacao()->franquia_id);
    //                 $subquery->where('id_status', '>=', 3);
    //                 $subquery->where('id_status', '!=', 10);
    //         })->count();

    //         if ($estoque - $itens_indisponiveis >= 0) {
    //             $disponibilidade = $estoque - $itens_indisponiveis;
    //         } else {
    //             $disponibilidade = 0;
    //         }    
    
    //     }
    //     else if ($franquia->tipo_franqueado == 'toy'){
    //         $estoque = 0;
    //         $itens_indisponiveis = 0;

    //         $estoque = Estoque::where('status', 'Disponível')
    //             ->where('id_franqueado', $franquiaId)
    //             ->where('id_produto', $produto->id)
    //             ->where('tipo_locacao', $request->modalidade)
    //             ->count();

    //         $produtoId = $produto->id;

    //         if($request->modalidade == 'aluguel'){
    //             $startDate = Carbon::parse($request->input('data_inicio'));
    //             $endDate = Carbon::parse($request->input('data_termino'));
    
    //             $itens_indisponiveis = ItensPedidos::where('id_produto', $produtoId)
    //                 ->where('tipo_locacao', 'aluguel')
    //                 ->whereHas('entrega', function ($query) use ($startDate, $endDate) {
    //                     $query->whereBetween('data_entrega', [$startDate, $endDate])
    //                         ->orWhereBetween('data_devolucao', [$startDate, $endDate]);
    //                 })
    //                 ->whereHas('pedido', function ($subquery) use ($franquiaId) {
    //                     $subquery->where('id_franquia', $franquiaId);
    //                     $subquery->where('id_status', '>=', 3);
    //                     $subquery->where('id_status', '!=', 10);
    //                 })->count();
    //         }
    //         else if ($request->modalidade == 'venda'){
    //             $itens_indisponiveis = ItensPedidos::where('id_produto', $produtoId)
    //                 ->where('tipo_locacao', 'venda')
    //                 ->whereHas('pedido', function ($subquery) use ($franquiaId) {
    //                     $subquery->where('id_franquia', $franquiaId);
    //                     $subquery->where('id_status', '>=', 3);
    //                     $subquery->where('id_status', '!=', 10);
    //                 })->count();
    //         }
    //         if ($estoque - $itens_indisponiveis >= 0) {
    //             $disponibilidade = $estoque - $itens_indisponiveis;
    //         } else {
    //             $disponibilidade = 0;
    //         }
    //     }

    //     return response()->json($disponibilidade);
    // }

    public function qtdMaxProduto(Request $request){

        $filtros = $request->except('_token');

        $produtoId = $request->input('id');
        $franquaiId = $request->input('franquia');
        $produtos = Produtos::find($produtoId);

        $produtos->whereHas('estoque',function($q) use ($franquaiId,$filtros){
            return $q->where('id_franqueado',$franquaiId);
        });
        $startDate = Carbon::parse($request->data_inicio);
        $endDate = Carbon::parse($request->data_termino);
       
        // $availableProducts = Estoque::where('status', 'Disponível')->whereDoesntHave('alugueis', function ($query) use ($startDate, $endDate) {
        //     $query->where(function ($query) use ($startDate, $endDate) {
        //         $query->where('data_entrega', '<=', $endDate)
        //               ->where('data_devolucao', '>=', $startDate)
        //               ->whereHas('pedido', function ($query2) {
        //                 $query2->where('id_status', '>=', 2); 
        //                 });
        //     });
        // })->where('id_produto',$produtoId)->get();

        $estoque = Estoque::where('status', 'Disponível')->where('id_franqueado', $franquaiId)
                 ->where('id_produto', $produtoId)
                 ->count();

        // $itens_indisponiveis = ItensPedidos::where('id_produto', $produtoId)
        // ->whereHas('entrega', function ($query) use ($startDate, $endDate) {
        //     $query->whereBetween('data_entrega', [$startDate, $endDate])
        //             ->orWhereBetween('data_devolucao', [$startDate, $endDate]);
        // })
        // ->whereHas('pedido', function ($subquery) use($franquaiId) {
        //     $subquery->where('id_franquia', $franquaiId);
        //     $subquery->where('id_status', '>=', 3);
        //     $subquery->where('id_status', '!=', 10);
        // })
        // ->count();
        $itens_indisponiveis = $this->verificarDisponibilidade($franquaiId, $produtoId, $startDate, $endDate);


        // $disponibilidade = $availableProducts->count();

        if ($estoque - $itens_indisponiveis >= 0){
            $disponibilidade = $estoque - $itens_indisponiveis;
        }
        else{
            $disponibilidade = 0;
        }

       return response()->json($disponibilidade);
    }

    public function store (Request $request){

        $data = $request->except('_token');

        if (Auth::user()->role != 'franqueado') {
            $franquia = Franquias::find($request->input('franquia'));
        } else {
            $franquia = Franquias::find($request->input('id_franquia'));
        }

        $prefix = $franquia->prefix;

        $randomNumber = mt_rand(1000, 9999);

        $numero = $prefix . '-' . $randomNumber;

        $token = Str::uuid()->toString();

        $pedido = Pedidos::create([
            'token' => $token,
            'numero' => $numero,
            'id_franquia' => $franquia->id,
            'id_cliente' => $request->input('id_cliente') ?? null,
            'id_status' => 1,
            'tipo' => 'manual',
        ]);

        $produtos = $request->input('produtos');
        $modalidade = $request->input('modalidade');
        $item = $request->input('item');
        $entrega = $request->input('entrega');
        if (!$produtos || $produtos['id_produto'][0] == 0) {
            return response()->json(['status' => 'error', 'message' => 'Um pedido precisa ter produtos para ser efetuado.'], 422);
        }

        $total_produtos = 0;

        if ($franquia->tipo_franqueado == 'trip'){
            foreach ($produtos['id_produto'] as $index => $produtosId) {
                if ($produtosId != 0) {
                    $maxqtd = intval($item['qtd'][$index]);
    
                    $startDate = $entrega['data_entrega'][$index];
                    $endDate = $entrega['data_devolucao'][$index];
    
                    for ($cont = 0; $cont < $maxqtd; $cont++) {
                        $itens_count = ItensPedidos::where('id_pedido', $pedido->id)->count();
                        $EstoqueOcupados = ItensPedidos::where('id_produto', $produtosId)
                            ->whereHas('entrega', function ($query) use ($startDate, $endDate) {
                                $query->whereBetween('data_entrega', [$startDate, $endDate])
                                    ->orWhereBetween('data_devolucao', [$startDate, $endDate]);
                            })
                            ->whereHas('pedido', function ($subquery) use ($pedido) {
                                $subquery->where('id_franquia', $pedido->id_franquia);
                                $subquery->where('id_status', '>=', 3);
                                $subquery->where('id_status', '!=', 10);
                                $subquery->where('id_status', '!=', 7);
                            })
                            ->pluck('id_item_estoque')->filter();
    
                        if ($itens_count == 0) {
                            $estoque = Estoque::where('id_produto', $produtosId)
                                ->where('status', 'Disponível')
                                ->where('id_franqueado', $pedido->id_franquia)
                                ->whereNotIn('id', $EstoqueOcupados)
                                ->first();
                        } else {
                            $estoqueUsados = 0;
                            $estoqueUsados = ItensPedidos::where('id_pedido', $pedido->id)->pluck('id_item_estoque');
                            $EstoqueOcupados = ItensPedidos::where('id_produto', $produtosId)
                                ->whereHas('entrega', function ($query) use ($startDate, $endDate) {
                                    $query->whereBetween('data_entrega', [$startDate, $endDate])
                                        ->orWhereBetween('data_devolucao', [$startDate, $endDate]);
                                })
                                ->whereHas('pedido', function ($subquery) use ($pedido) {
                                    $subquery->where('id_franquia', $pedido->id_franquia);
                                    $subquery->where('id_status', '>=', 3);
                                    $subquery->where('id_status', '!=', 10);
                                    $subquery->where('id_status', '!=', 7);
                                })
                                ->pluck('id_item_estoque');
                            $estoque = Estoque::where('id_produto', $produtosId)
                                ->where('status', 'Disponível')
                                ->where('id_franqueado', $pedido->id_franquia)
                                ->whereNotIn('id', $EstoqueOcupados)
                                ->whereNotIn('id', $estoqueUsados)
                                ->first();
                        }
    
                        $itens = new ItensPedidos();
                        $itens->id_pedido = $pedido->id;
                        $itens->id_produto = $produtosId;
                        if ($estoque) {
                            $itens->id_item_estoque = $estoque->id;
                        }
                        $itens->qtd = 1;
                        $item['valor_unitario'][$index] = floatval(str_replace(',', '.', str_replace('.', '', $item['valor_unitario'][$index])));
                        $item['valor_total'][$index] = floatval(str_replace(',', '.', str_replace('.', '', $item['valor_total'][$index])));
                        $itens->valor_unitario = $item['valor_unitario'][$index];
                        $itens->valor_total = $item['valor_total'][$index];
                        $itens->id_entrega_pedido = null;
                        $itens->save();
    
                        $entrega_pedido = new EntregaPedido();
                        $entrega_pedido->id_pedido = $pedido->id;
                        $entrega_pedido->id_itens_pedido = $itens->id;
                        if ($estoque) {
                            $entrega_pedido->id_item_estoque = $estoque->id;
                        }
                        $entrega_pedido->data_entrega = $entrega['data_entrega'][$index];
                        $entrega_pedido->data_devolucao = $entrega['data_devolucao'][$index];
                        $entrega_pedido->save();
    
                        $itens->update([
                            'id_entrega_pedido' => $entrega_pedido->id
                        ]);
                    }
    
                    $total_produtos += $item['valor_total'][$index];
                }
            }
        }
        else{
            foreach ($produtos['id_produto'] as $index => $produtosId) {
                if ($produtosId != 0) {
                    $maxqtd = intval($item['qtd'][$index]);

                    if ($modalidade[$index] == 'aluguel'){
                        $endDate = $entrega['data_devolucao'][$index];
                    }
    
                    $startDate = $entrega['data_entrega'][$index];
    
                    for ($cont = 0; $cont < $maxqtd; $cont++) {
                        if ($modalidade[$index] == 'aluguel'){
                            $itens_count = ItensPedidos::where('id_pedido', $pedido->id)->count();
                            $EstoqueOcupados = ItensPedidos::where('id_produto', $produtosId)
                                ->where('tipo_locacao', 'aluguel')
                                ->whereHas('entrega', function ($query) use ($startDate, $endDate) {
                                    $query->whereBetween('data_entrega', [$startDate, $endDate])
                                        ->orWhereBetween('data_devolucao', [$startDate, $endDate]);
                                })
                                ->whereHas('pedido', function ($subquery) use ($pedido) {
                                    $subquery->where('id_franquia', $pedido->id_franquia);
                                    $subquery->where('id_status', '>=', 3);
                                    $subquery->where('id_status', '!=', 10);
                                    $subquery->where('id_status', '!=', 7);
                                })
                                ->pluck('id_item_estoque')->filter();
        
                            if ($itens_count == 0) {
                                $estoque = Estoque::where('id_produto', $produtosId)
                                    ->where('status', 'Disponível')
                                    ->where('id_franqueado', $pedido->id_franquia)
                                    ->where('tipo_locacao', 'aluguel')
                                    ->whereNotIn('id', $EstoqueOcupados)
                                    ->first();
                            } else {
                                $estoqueUsados = 0;
                                $estoqueUsados = ItensPedidos::where('id_pedido', $pedido->id)->pluck('id_item_estoque');
                                $EstoqueOcupados = ItensPedidos::where('id_produto', $produtosId)
                                    ->where('tipo_locacao', 'aluguel')
                                    ->whereHas('entrega', function ($query) use ($startDate, $endDate) {
                                        $query->whereBetween('data_entrega', [$startDate, $endDate])
                                            ->orWhereBetween('data_devolucao', [$startDate, $endDate]);
                                    })
                                    ->whereHas('pedido', function ($subquery) use ($pedido) {
                                        $subquery->where('id_franquia', $pedido->id_franquia);
                                        $subquery->where('id_status', '>=', 3);
                                        $subquery->where('id_status', '!=', 10);
                                        $subquery->where('id_status', '!=', 7);
                                    })
                                    ->pluck('id_item_estoque');
                                $estoque = Estoque::where('id_produto', $produtosId)
                                    ->where('status', 'Disponível')
                                    ->where('tipo_locacao', 'aluguel')
                                    ->where('id_franqueado', $pedido->id_franquia)
                                    ->whereNotIn('id', $EstoqueOcupados)
                                    ->whereNotIn('id', $estoqueUsados)
                                    ->first();
                            }

                            $itens = new ItensPedidos();
                            $itens->id_pedido = $pedido->id;
                            $itens->id_produto = $produtosId;
                            if ($estoque) {
                                $itens->id_item_estoque = $estoque->id;
                            }
                            $itens->qtd = 1;
                            $item['valor_unitario'][$index] = floatval(str_replace(',', '.', str_replace('.', '', $item['valor_unitario'][$index])));
                            $item['valor_total'][$index] = floatval(str_replace(',', '.', str_replace('.', '', $item['valor_total'][$index])));
                            $itens->valor_unitario = $item['valor_unitario'][$index];
                            $itens->valor_total = $item['valor_total'][$index];
                            $itens->id_entrega_pedido = null;
                            $itens->save();
        
                            $entrega_pedido = new EntregaPedido();
                            $entrega_pedido->id_pedido = $pedido->id;
                            $entrega_pedido->id_itens_pedido = $itens->id;
                            if ($estoque) {
                                $entrega_pedido->id_item_estoque = $estoque->id;
                            }
                            $entrega_pedido->data_entrega = $entrega['data_entrega'][$index];
                            $entrega_pedido->data_devolucao = $entrega['data_devolucao'][$index];
                            $entrega_pedido->save();
        
                            $itens->update([
                                'id_entrega_pedido' => $entrega_pedido->id
                            ]);
                        }
                        else{
                            $itens_count = ItensPedidos::where('id_pedido', $pedido->id)->count();
                            $EstoqueOcupados = ItensPedidos::where('id_produto', $produtosId)
                                ->where('tipo_locacao', 'venda')
                                ->whereHas('pedido', function ($subquery) use ($pedido) {
                                    $subquery->where('id_franquia', $pedido->id_franquia);
                                    $subquery->where('id_status', '>=', 3);
                                    $subquery->where('id_status', '!=', 10);
                                    $subquery->where('id_status', '!=', 7);
                                })
                                ->pluck('id_item_estoque')->filter();
        
                            if ($itens_count == 0) {
                                $estoque = Estoque::where('id_produto', $produtosId)
                                    ->where('status', 'Disponível')
                                    ->where('tipo_locacao', 'venda')
                                    ->where('id_franqueado', $pedido->id_franquia)
                                    ->whereNotIn('id', $EstoqueOcupados)
                                    ->first();
                            } else {
                                $estoqueUsados = 0;
                                $estoqueUsados = ItensPedidos::where('id_pedido', $pedido->id)->pluck('id_item_estoque');
                                $EstoqueOcupados = ItensPedidos::where('id_produto', $produtosId)
                                    ->where('tipo_locacao', 'venda')
                                    ->whereHas('pedido', function ($subquery) use ($pedido) {
                                        $subquery->where('id_franquia', $pedido->id_franquia);
                                        $subquery->where('id_status', '>=', 3);
                                        $subquery->where('id_status', '!=', 10);
                                        $subquery->where('id_status', '!=', 7);
                                    })
                                    ->pluck('id_item_estoque');
                                $estoque = Estoque::where('id_produto', $produtosId)
                                    ->where('status', 'Disponível')
                                    ->where('id_franqueado', $pedido->id_franquia)
                                    ->where('tipo_locacao', 'venda')
                                    ->whereNotIn('id', $EstoqueOcupados)
                                    ->whereNotIn('id', $estoqueUsados)
                                    ->first();
                            }
                            $itens = new ItensPedidos();
                            $itens->id_pedido = $pedido->id;
                            $itens->id_produto = $produtosId;
                            if ($estoque) {
                                $itens->id_item_estoque = $estoque->id;
                            }
                            $itens->qtd = 1;
                            $item['valor_unitario'][$index] = floatval(str_replace(',', '.', str_replace('.', '', $item['valor_unitario'][$index])));
                            $item['valor_total'][$index] = floatval(str_replace(',', '.', str_replace('.', '', $item['valor_total'][$index])));
                            $itens->valor_unitario = $item['valor_unitario'][$index];
                            $itens->valor_total = $item['valor_total'][$index];
                            $itens->id_entrega_pedido = null;
                            $itens->save();
        
                            $entrega_pedido = new EntregaPedido();
                            $entrega_pedido->id_pedido = $pedido->id;
                            $entrega_pedido->id_itens_pedido = $itens->id;
                            if ($estoque) {
                                $entrega_pedido->id_item_estoque = $estoque->id;
                            }
                            $entrega_pedido->data_entrega = $entrega['data_entrega'][$index];
                            $entrega_pedido->data_devolucao = null;
                            $entrega_pedido->save();
        
                            $itens->update([
                                'id_entrega_pedido' => $entrega_pedido->id
                            ]);
                        }
                    }
    
                    $total_produtos += $item['valor_total'][$index];
                }
            }
        }

        $valor_frete = 0;
        if ($request->filled('tipo_frete')) {
            if ($request->input('tipo_frete') == 'economico') {
                $valor_frete = $request->input('valor_economico');

                $pedido->update(['tipo_frete' => 'economico', 'valor_frete' => $valor_frete]);
            } else if ($request->input('tipo_frete') == 'expresso') {
                $valor_frete = $request->input('valor_expresso');
                $pedido->update(['tipo_frete' => 'expresso', 'valor_frete' => $valor_frete]);
            } else if ($request->input('tipo_frete') == 'retirar_loja') {
                $pedido->update(['tipo_frete' => 'retirar_loja', 'valor_frete' => '00.00', 'id_retirada' => $request->input('id_retirada')]);
            }
        }

        $total = $total_produtos + $valor_frete;

        if ($franquia->tipo_franqueado == 'trip'){
            $valor_liquido = $total;
            $pagamento = 'total';
        }
        else{
            if ($request->input('pagamento') == 'parcial'){
                $valor_liquido = floatval(str_replace(',', '.', str_replace('.', '', $request->input('valorparcial'))));
                $pagamento = 'parcial';
            }
            else{
                $valor_liquido = $total;
                $pagamento = 'total';
            }
        }

        $pedido->update(['pagamento' => $pagamento, 'valor_total_produtos' => $total_produtos, 'valor_total' => $total, 'valor_liquido' => $valor_liquido, 'observacoes_internas' => $request->input('observacoes_internas'), 'observacoes_cliente' => $request->input('observacoes_cliente')]);

        return response()->json($pedido->url());
    }

    public function preview(Request $request, $id)
    {
        $pedido = Pedidos::find($id);
        $itens = ItensPedidos::where('id_pedido', $pedido->id)->get();

        $data_entrega = null;
        $data_devolucao = null;

        foreach ($itens as $item) {
            if ($item->entrega) {
                if ($data_entrega === null || $item->entrega->data_entrega < $data_entrega) {
                    $data_entrega = $item->entrega->data_entrega ?? '';
                }
                if ($data_devolucao === null || $item->entrega->data_devolucao > $data_devolucao) {
                    $data_devolucao = $item->entrega->data_devolucao ?? '';
                }
            }
        }

        $franquias = Franquias::where('status', 'ativo')->where('retirada_balcao', 'sim')->get();

        return view('admin.pedidos.mostrar', compact('pedido', 'data_entrega', 'data_devolucao', 'franquias'));
    }

    public function mudarItens($id = null, $idItem = null)
    {
        $item = ItensPedidos::find($idItem);

        $estoque = Estoque::find($id);

        $entrega_pedido = EntregaPedido::where('id_itens_pedido', $item->id)->first();

        $item->update(['id_item_estoque' => $estoque->id]);
        $entrega_pedido->update(['id_item_estoque' => $estoque->id]);

        return response()->json($estoque);
    }
    public function mudarUnidade($id = null, $idFranquia = null)
    {
        $franquia = Franquias::find($idFranquia);

        $pedido = Pedidos::find($id);

        $pedido->update(['id_retirada' => $franquia->id]);

        return response()->json(['status' => 'ok']);
    }
    public function mudarStatusEntregueDevolvido($id = null, $tipo = null)
    {
        $pedido = Pedidos::find($id);
        return view('admin.pedidos._modal-itens', compact('pedido', 'tipo'));
    }
    public function periodoFranquias(Request $request)
    {
        $franquia = Franquias::find($request->franquia);
        return view('admin.pedidos._periodos', compact('franquia'));
    }

    public function endereco($id = null, Request $request)
    {
        $data = $request->except('_token');

        $dados = $request->all();
        $franquia = Franquias::find($id);

        $bairro = str_replace('Loteamento ', '', $dados['bairro']);
        $bairro = '%' . $bairro . '%';
        $cidade = '%' . $dados['localidade'] . '%';
        $estado = $dados['uf'];

        $regiaobairro = RegiaoAtendida::where('id_franqueado', $franquia->id)
            ->where('bairro', 'like', $bairro)
            ->where('cidade', 'like', $cidade)->where('estado', $estado)->where('tipo', 'bairro')->first();
        if ($regiaobairro){
            $regiao = $regiaobairro;
        }
        else{
            $regiao = RegiaoAtendida::where('id_franqueado', $franquia->id)
            ->where('cidade', 'like', $cidade)->where('estado', 'like', $estado)->where('tipo', 'cidade')->first();
        }
        if ($regiao) {
            $expresso = $regiao->valor_entrega_expresso + $regiao->valor_entrega_expresso;
            $economico = $regiao->valor_entrega_economico + $regiao->valor_entrega_economico;
            $frete_economico = $franquia->frete_economico;
            $frete_expresso = $franquia->frete_expresso;
            $retirada_balcao = $franquia->retirada_balcao;
            return ['expresso' => $expresso, 'economico' => $economico, 'frete_economico' => $frete_economico, 'frete_expresso' => $frete_expresso, 'retirada_balcao' => $retirada_balcao];
        } else {
            return response()->json(['error' => 'erro'], 403);
        }
    }

    public function EntregueDevolvido($id, Request $request)
    {
        $data = $request->except('_token');

        $pedido = Pedidos::find($id);

        // dd($data);
        $entregas = EntregaPedido::where('id_pedido', $id)->get();
        $itens = $request->input('itens');
        $controler = 0;

        foreach ($itens as $item) {
            foreach ($entregas as $entrega) {
                if ($item == $entrega->id_itens_pedido) {
                    $entrega->update(['status' => 'inativo']);
                    $controler++;
                    break;
                }
            }
        }

        if ($entregas->count() == $controler) {
            if ($data['tipo'] == 'entregue') {
                $pedido->update(['id_status' => 6]);
            } else if ($data['tipo'] == 'devolvido') {
                $pedido->update(['id_status' => 7]);
            }
        }
        return response()->json(['status' => 'ok']);
    }

    public function mudarStatus($id = null, $idStatus = null)
    {
        $pedido = Pedidos::find($id);

        $pedido->update(['id_status' => $idStatus]);

        return response()->json(['status' => 'ok']);
    }

    public function descontos(Request $request)
    {
        $produtoId = $request->input('id');
        $dias = $request->input('daysDifference');
        $produto = Produtos::findOrFail($produtoId);

        $descontos = Promocoes::where('id_produto', $produto->id)->get();
        $promocao = 0;

        foreach ($descontos as $desconto) {
            if (!$desconto->ate) {
                $desconto->ate = 1000;
            }
            if ($dias >= $desconto->de && $dias <= $desconto->ate) {
                if ($desconto->tipo == 'porcentagem') {
                    $promocao = ($produto->valor_base_diaria * ($desconto->desconto / 100));
                } elseif ($desconto->tipo == 'real') {
                    $promocao = $desconto->desconto;
                }
            }
        }

        $diario = $produto->valor_base_diaria - $promocao;

        if ($dias <= 3) {
            $total = $diario * 3;
        } else {
            $total = $diario * $dias;
        }

        $valores = [
            'total' => $total,
            'diario' => $diario
        ];

        return response()->json($valores);
    }

    function verificarDisponibilidade($franquaiId, $produtoId, $startDate, $endDate)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
        

        $idsEntregas = ItensPedidos::with(['entrega', 'pedido'])
            ->where('id_produto', $produtoId)
            ->whereHas('entrega', function ($query) use ($startDate) {
                $query->where('data_devolucao', '>=', $startDate->format('Y-m-d'));
            })
            ->whereHas('pedido', function ($subquery) use ($franquaiId) {
                $subquery->where('id_franquia', $franquaiId)
                    ->where('id_status', '>=', 3)
                    ->where('id_status', '!=', 10)
                    ->where('id_status', '!=', 7);
            })
            ->pluck('id');

        if ($idsEntregas->isEmpty()) {
            return 0;
        }

        $idsEntregasArray = $idsEntregas->toArray();
        $placeholders = implode(',', array_fill(0, count($idsEntregasArray), '?'));

        $query = "
            WITH cte_ranked AS (
                SELECT 
                    ip.*, 
                    e.data_entrega,
                    e.data_devolucao,
                    p.nome_receber,
                    ROW_NUMBER() OVER (
                        PARTITION BY ip.id_item_estoque 
                        ORDER BY ip.id ASC, ip.created_at ASC
                    ) AS row_num
                FROM itens_pedido ip
                INNER JOIN entrega_pedido e ON ip.id_entrega_pedido = e.id
                INNER JOIN pedidos p ON ip.id_pedido = p.id
                WHERE e.id IN ($placeholders)
                AND e.data_entrega <= ? 
                AND p.id_franquia = ?
                AND p.id_status >= 3
                AND p.id_status != 10
                AND p.id_status != 7
            )
            SELECT * 
            FROM cte_ranked
            WHERE row_num = 1
        ";

        $params = array_merge($idsEntregasArray, [$endDate->format('Y-m-d'), $franquaiId]);

        $result = DB::select($query, $params);

        $itensIndisponiveis = collect($result)->count();

        return
            $itensIndisponiveis;
        }

}
