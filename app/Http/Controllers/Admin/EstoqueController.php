<?php

namespace App\Http\Controllers\Admin;

use App\Models\Estoque;
use App\Models\Franquias;
use App\Models\Pedidos;
use App\Models\Produtos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EstoqueController extends Controller
{

    public function index(Request $request)
    {
        $franquia = $request->input('franquia');
        $query = Estoque::query();

        if (Auth::user()->role == 'franqueado') {
            $query->where('id_franqueado', Auth::user()->id_franquia)->orderBy('id', 'desc')->paginate();

            if (Auth::user()->franquia->tipo_franqueado == 'toy'){
                $selecioneProdutos = Produtos::where('produto_catalogo', 'nao')->where('status', 'ativo')->where('id_franquia', auth::user()->id_franquia)->select()->distinct()->get();
            }
            else{
                $selecioneProdutos = Produtos::where('produto_catalogo', 'nao')->where('status', 'ativo')->where('tipo', 'trip')->select()->distinct()->get();
            }
            
        } else {
            if ($franquia === 'trip') {
                $query->whereHas('franquia', function ($q) {
                    $q->where('tipo_franqueado', 'trip');
                });
            } elseif ($franquia === 'toy') {

                $query->whereHas('franquia', function ($q) {
                    $q->where('tipo_franqueado', 'toy');
                });
            }
            $selecioneProdutos = Produtos::where('produto_catalogo', 'nao')->where('status', 'ativo')->select()->distinct()->get();
        }
        
        if ($request->has('termo')) {

            $termo = $request->input('termo');

            $query->whereHas('produto', function ($q) use ($termo) {
                $q->where('nome', 'LIKE', "%$termo%");
            });
        }

        $estoques = $query->orderBy('id', 'desc')->paginate(10);
        
        $selecionarFranquia = Franquias::where('status', 'ativo')->select()->distinct()->get();

        if ($request->ajax()) {

            return view('admin.estoque._list', compact('estoques', 'selecioneProdutos', 'selecionarFranquia'))->render();
        }

        return view('admin.estoque.index', compact('estoques', 'selecioneProdutos', 'selecionarFranquia'));
    }

    // public function searchProduto(Request $request)
    // {
        
    //     $franquia = Franquias::find($id); dd($franquia);

    //     if($franquia->tipo_franqueado == 'toy'){
    //         $selecioneProdutos = Produtos::where('produto_catalogo', 'nao')->where('status', 'ativo')->where('id_franquia', $id)->select()->distinct()->get();
    //     }
    //     else if($franquia->tipo_franqueado == 'trip'){
    //         $selecioneProdutos = Produtos::where('produto_catalogo', 'nao')->where('status', 'ativo')->where('tipo', 'trip')->select()->distinct()->get();
    //     }

    //     return view('admin.estoque._listProduto', compact('selecioneProdutos'));
    // }



    public function store(Request $request)
    {

        $data = $request->except('_token');

        if (Auth::user()->role == 'franqueado') {
            $id_franqueado = Auth::user()->id_franquia;
        } else {
            $id_franqueado = $data['id_franqueado'];
        }

        $franquia = Franquias::find($id_franqueado);

        $codigo_base = strtoupper($data['codigo']);
        $qtd = $data['qtd'];

        // Gerar todos os códigos
        $codigos = [];
        for ($i = 1; $i <= $qtd; $i++) {
            $codigos[] = $codigo_base . '_' . str_pad($i, 3, '0', STR_PAD_LEFT);
        }

        // Verificar se algum código já existe
        $existing_codes = Estoque::whereIn('codigo', $codigos)->where('id_franqueado', $id_franqueado)->pluck('codigo')->toArray();

        if (!empty($existing_codes)) {
            return response()->json(['error' => 'codigo', 'msg' => 'Um ou mais código já existem'], 422);
        }

        // Inserir os registros em lote
        $estoques = [];
        if ($request->filled('valor_compra')) {
            $data['valor_compra'] = str_replace(',', '.', str_replace('.', '', $data['valor_compra']));
        }
        
        for ($i = 1; $i <= $qtd; $i++) {
            if ($franquia->tipo_franqueado == 'trip') {
                $estoques[] = [
                    'id_franqueado' => $id_franqueado,
                    'id_produto' => $data['id_produto'],
                    'tipo_locacao' => 'aluguel',
                    'codigo' => $codigo_base . '_' . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'data_compra' => $data['data_compra'] ?? null,
                    'valor_compra' => $data['valor_compra'] ?? null,
                    'status' => 'Disponível',
                ];
            } else {
                $estoques[] = [
                    'id_franqueado' => $id_franqueado,
                    'id_produto' => $data['id_produto'],
                    'tipo_locacao' => $data['tipo_locacao'],
                    'codigo' => $codigo_base . '_' . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'data_compra' => $data['data_compra'] ?? null,
                    'valor_compra' => $data['valor_compra'] ?? null,
                    'status' => 'Disponível',
                ];
            }
        }

        Estoque::insert($estoques);
    }


    public function edit($id)
    {
        $estoque = Estoque::find($id); 

        return response()->json($estoque);
    }

    public function update(Request $request)
    {

        $data = $request->except('_token');


        if (Auth::user()->role == 'franqueado') {
            $id_franqueado = Auth::user()->id_franquia;
        } else {
            $id_franqueado = $data['id_franqueado'];
        }

        $existing_codes = Estoque::where('codigo', $data['codigo'])->where('id_franqueado', $id_franqueado)->first();

        if (!empty($existing_codes) && $existing_codes->id != $data['id']) {
            return response()->json(['error' => 'codigo', 'msg' => 'Um ou mais código já existem'], 422);
        }

        if ($request->filled('valor_compra')) {
            $data['valor_compra'] = str_replace(',', '.', str_replace('.', '', $data['valor_compra']));
        }

        $estoque = Estoque::where('id', $data['id'])->update([
            'id_franqueado' => $id_franqueado,
            'codigo' => strtoupper($data['codigo']),
            'tipo_locacao' => $data['tipo_locacao'],
            'data_compra' => $data['data_compra'] ?? null,
            'valor_compra' => $data['valor_compra'] ?? null,
        ]);

        return response()->json('editado');
    }

    public function delete(Request $request, $id)
    {

        $estoque = Estoque::find($id);
        if($estoque->alugueis()->exists()){
            $estoque->status = 'Indisponível';
            $estoque->save();
            return response()->json(['status' => 'error', 'message' => 'Estoque associado a alugueis e não pode ser deletado. O estoque foi definido como indisponível.']);

        }else{
            $estoque->delete();
            return response()->json(['status' => 'ok']);
        }

    }

    public function mudarStatus($id = null, $status = null)
    {
        $estoque = Estoque::find($id);

        $estoque->update(['status' => $status]);

        return response()->json(['status' => 'ok']);
    }
}
