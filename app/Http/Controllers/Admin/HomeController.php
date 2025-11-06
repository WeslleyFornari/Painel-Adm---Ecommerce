<?php

namespace App\Http\Controllers\Admin;

use App\Models\Carrinhos;
use App\Models\Categorias;
use App\Models\Cupons;
use App\Models\Empresas;
use App\Models\EntregaPedido;
use App\Models\Estoque;
use App\Models\FormasPagamentos;
use App\Models\Franquias;
use App\Models\Grupos;
use App\Models\ItensPedidos;
use App\Models\Pagamentos;
use App\Models\Pedidos;
use App\Models\Produtos;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */






    public function index()
    {
        //  if (Auth::user()->role == 'user') {
        //      return redirect(route('site.index'));
        //  }

        $franquia_id = Auth::user()->id_franquia;

        $clientes = User::whereHas('pedidos', function ($query) use ($franquia_id) {
            if (!empty($franquia_id)) {
                $query->where('id_franquia', $franquia_id);
            }
        })->count();

        $cupons = Cupons::whereHas('pedidos', function ($query) use ($franquia_id) {
            if (!empty($franquia_id)) {
                $query->where('id_franquia', $franquia_id);
            }
        })->count();

        $pedidos = Pedidos::where('id_status', '>=', 3)->where('id_status', '!=', 10)->when($franquia_id, function ($query) use ($franquia_id) {
            return $query->where('id_franquia', $franquia_id);
        })->paginate(30);

        $pedidos_qtd = Pedidos::where('id_status', '>=', 3)->where('id_status', '!=', 10)->when($franquia_id, function ($query) use ($franquia_id) {
            return $query->where('id_franquia', $franquia_id);
        })->count();

        $faturamento_total = Pedidos::where('id_status', '>=', 3)->where('id_status', '!=', 10)->when($franquia_id, function ($query) use ($franquia_id) {
            return $query->where('id_franquia', $franquia_id);
        })->sum('valor_total');

        $entregas = EntregaPedido::when($franquia_id, function ($query) use ($franquia_id) {
            return $query->whereHas('pedido', function ($query) use ($franquia_id) {
                $query->where('id_franquia', $franquia_id);
            });
        })->select('id_pedido', 'data_entrega', DB::raw('MAX(id) as id_max'))
            ->orderBy('data_entrega', 'desc')
            ->groupBy('id_pedido', 'data_entrega')
            ->paginate(20);

        $devolucoes = EntregaPedido::when($franquia_id, function ($query) use ($franquia_id) {
            return $query->whereHas('pedido', function ($query) use ($franquia_id) {
                $query->where('id_franquia', $franquia_id);
            });
        })->select('id_pedido', 'data_devolucao', DB::raw('MAX(id) as id_max'))
            ->orderBy('data_devolucao', 'desc')
            ->groupBy('id_pedido', 'data_devolucao')
            ->paginate(10);

        $franquias = Franquias::where('status', 'ativo')->get();

     
        $pedAnual = $this->pedidosAnual();
        $pedMensal = $this->pedidosMensal(); //dd($pedMensal);

        return view('admin.dashboard.index', compact('pedAnual','pedMensal','entregas', 'devolucoes', 'pedidos', 'clientes', 'faturamento_total', 'pedidos_qtd', 'cupons', 'franquias'));
    }

    // Plataforma X Manual


    public function pedidosAnual()
    {
        $pedidosAnual = [
            'manual' => Pedidos::where('tipo', 'manual')->count(),
            'automatico' => Pedidos::where('tipo', 'automatico')->count(),
        ];

        return $pedidosAnual;
    }


    public function pedidosMensal()
    {
        $meses = collect(range(0, 5))->map(function ($i) {
            return now()->subMonths($i)->format('Y-m');
        })->reverse();
    
        $pedidosMensal = $meses->mapWithKeys(function ($mes) {
            $start = Carbon::parse($mes . '-01')->startOfMonth();
            $end = Carbon::parse($mes . '-01')->endOfMonth();
    
            return [
                $mes => [
                    'manual' => Pedidos::where('tipo', 'manual')
                        ->whereBetween('created_at', [$start, $end])
                        ->count(),
                    'automatico' => Pedidos::where('tipo', 'automatico')
                        ->whereBetween('created_at', [$start, $end])
                        ->count(),
                ]
            ];
        });
    
        return $pedidosMensal;
    }

    public function getEntregas(Request $request)
    {
        $query = EntregaPedido::select('id_pedido', 'data_entrega', DB::raw('MAX(id) as id_max'))
            ->orderBy('data_entrega', 'desc')
            ->groupBy('id_pedido', 'data_entrega');

        if (Auth::user()->id_franquia) {
            $query->whereHas('pedido', function ($q) {
                $q->where('id_franquia', Auth::user()->id_franquia);
            });
        }

        $entregas = $query->paginate(20);

        if ($request->ajax()) {
            return view('admin.dashboard.include._list-entregas', compact('entregas'))->render();
        }

        return redirect()->route('home');
    }

    public function getDevolucoes(Request $request)
    {
        $query = EntregaPedido::select('id_pedido', 'data_devolucao', DB::raw('MAX(id) as id_max'))
            ->orderBy('data_devolucao', 'desc')
            ->groupBy('id_pedido', 'data_devolucao');

        if (Auth::user()->id_franquia) {
            $query->whereHas('pedido', function ($q) {
                $q->where('id_franquia', Auth::user()->id_franquia);
            });
        }

        $devolucoes = $query->paginate(20);

        if ($request->ajax()) {
            return view('admin.dashboard.include._list-devolucoes', compact('devolucoes'))->render();
        }

        return redirect()->route('home');
    }

    public function graficosQtdPedidos()
    {
        $data = [];
        $months = ["JAN", "FEV", "MAR", "ABR", "MAI", "JUN", "JUL", "AGO", "SET", "OUT", "NOV", "DEZ"];
        $now = Carbon::now();

        $userFranquiaId = auth()->user()->id_franquia ?? null;

        for ($i = 0; $i < 12; $i++) {
            $date = $now->copy()->subMonths($i);
            $month = $date->month;
            $year = $date->year;
            $monthName = $months[$month - 1];

            $pedidosQuery = Pedidos::where('id_status', '>=', 3)->where('id_status', '!=', 10)->whereMonth('created_at', $month)
                ->whereYear('created_at', $year);
            $carrinhosQuery = Carrinhos::whereMonth('created_at', $month)
                ->whereYear('created_at', $year);

            if ($userFranquiaId) {
                $pedidosQuery->where('id_franquia', $userFranquiaId);
                $carrinhosQuery->where('id_franquia', $userFranquiaId);
            }

            $pedidos = $pedidosQuery->get();
            $carrinhos = $carrinhosQuery->get();
            $pedidos_pagos = $pedidos->where('id_status', '>=', '3')->where('id_status', '!=', 10);
            $pedidos_pendentes = $pedidos->where('id_status', '<=', '2');

            if ($pedidos->count() > 0 || $carrinhos->count() != 0) {
                $data[] = [
                    'month' => "$monthName/$year",
                    'carrinhos' => $carrinhos->count(),
                    'qtd' => $pedidos->count(),
                    'valor_pagos' => $pedidos_pagos->sum('valor_total'),
                    'valor_pendentes' => $pedidos_pendentes->sum('valor_total'),
                    'ticket_medio' => $pedidos_pagos->count() > 0 ? $pedidos_pagos->sum('valor_total') / $pedidos_pagos->count() : 0,
                ];
            }
        }

        $data = array_reverse($data);

        return response()->json($data);
    }

    public function graficosPedidoSemana()
    {
        $data = [];
        $diasDaSemana = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];

        $userFranquiaId = auth()->user()->id_franquia ?? null;

        for ($i = 0; $i < 7; $i++) {
            $dayName = $diasDaSemana[$i];
            $dayOfWeek = $i + 1;

            $pedidosQuery = Pedidos::where('id_status', '>=', 3)->where('id_status', '!=', 10)->whereRaw('DAYOFWEEK(created_at) = ?', [$dayOfWeek]);

            if ($userFranquiaId) {
                $pedidosQuery->where('id_franquia', $userFranquiaId);
            }

            $pedidos = $pedidosQuery->get();

            $data[] = [
                'day' => $dayName,
                'qtd' => $pedidos->count(),
            ];
        }

        return response()->json($data);
    }
    public function graficosProdutosMaisAlugados($franquia = null)
    {
        $produtosData = [['Produto', 'Quantidade']];

        $query = ItensPedidos::select(
            DB::raw('COUNT(*) as quantidade'),
            'id_produto'
        )
            ->groupBy('id_produto')
            ->with('produto')
            ->orderBy('quantidade', 'desc')
            ->limit(5);

        if ($franquia != 'todos') {

            $query = ItensPedidos::whereHas('pedido', function ($subQuery) use ($franquia) {
                $subQuery->where('id_franquia', $franquia);
            })->select(
                DB::raw('COUNT(*) as quantidade'),
                'id_produto'
            )
                ->groupBy('id_produto')
                ->with('produto')
                ->orderBy('quantidade', 'desc')
                ->limit(5);
        } else {
            $query = ItensPedidos::select(
                DB::raw('COUNT(*) as quantidade'),
                'id_produto'
            )
                ->groupBy('id_produto')
                ->with('produto')
                ->orderBy('quantidade', 'desc')
                ->limit(5);
        }

        $itensPedido = $query->get();

        foreach ($itensPedido as $item) {
            $produtosData[] = [$item->produto->nome ?? '', (int) $item->quantidade];
        }

        return response()->json($produtosData);
    }




    public function graficosPedidoSemanaTodos()
    {
        $data = [];
        $franquiasData = [];
        $diasDaSemana = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];

        $franquias = Franquias::where('status', 'ativo')->get();

        foreach ($diasDaSemana as $i => $dayName) {
            $dados = [
                'semana' => $dayName,
                'franquias' => []
            ];

            foreach ($franquias as $franquia) {
                $pedidos = Pedidos::where('id_status', '>=', 3)->where('id_status', '!=', 10)->whereRaw('DAYOFWEEK(created_at) = ?', [$i + 1])
                    ->where('id_franquia', $franquia->id)
                    ->get();

                $dados['franquias'][] = [
                    'nome_franquia' => $franquia->nome_franquia,
                    'qtd' => $pedidos->count(),
                ];

                // Adiciona franquia ao array franquiasData apenas se ainda não estiver lá
                if (!in_array(['nome' => $franquia->nome_franquia], $franquiasData)) {
                    $franquiasData[] = [
                        'nome' => $franquia->nome_franquia,
                    ];
                }
            }

            $data[] = $dados;
        }

        return response()->json([
            'data' => $data,
            'franquias' => $franquiasData,
        ]);
    }

    public function graficosValorPedidosTodos()
    {
        $data = [];
        $franquiasData = [];
        $months = ["JAN", "FEV", "MAR", "ABR", "MAI", "JUN", "JUL", "AGO", "SET", "OUT", "NOV", "DEZ"];
        $now = Carbon::now();

        $franquias = Franquias::where('status', 'ativo')->get();
        for ($i = 0; $i < 12; $i++) {
            $date = $now->copy()->subMonths($i);
            $month = $date->month;
            $year = $date->year;

            $monthName = $months[$month - 1];
            $monthData = [
                'month' => "$monthName/$year",
                'franquias' => []
            ];

            foreach ($franquias as $franquia) {
                $pedidos_pagos = Pedidos::whereMonth('created_at', $month)
                    ->whereYear('created_at', $year)
                    ->where('id_franquia', $franquia->id)
                    ->where('id_status', '>=', '3')
                    ->where('id_status', '!=', 10);

                $pedidos_pendentes = Pedidos::whereMonth('created_at', $month)
                    ->whereYear('created_at', $year)
                    ->where('id_franquia', $franquia->id)
                    ->where('id_status', '<=', '2');

                if ($pedidos_pagos->count() != 0 ||  $pedidos_pendentes->count() != 0) {
                    $monthData['franquias'][] = [
                        'nome_franquia' => $franquia->nome_franquia,
                        'valor_pagos' => $pedidos_pagos->sum('valor_total'),
                        'qtd' => $pedidos_pagos->count() + $pedidos_pendentes->count(),
                        'valor_pendentes' => $pedidos_pendentes->sum('valor_total'),
                        'ticket_medio' => $pedidos_pagos->count() > 0 ? $pedidos_pagos->sum('valor_total') / $pedidos_pagos->count() : 0,
                    ];
                }

                $franquiasData[] = [
                    'nome' => $franquia->nome_franquia,
                ];
            }

            $data[] = $monthData;
        }

        return response()->json([
            'data' => array_reverse($data),
            'franquias' => array_unique($franquiasData, SORT_REGULAR),
        ]);
    }


    public function graficosFranquiaPorDia($data = null)
    {
        $franquias = Franquias::where('status', 'ativo')->get();
        $dataInicio = $data . ' 00:00:00';
        $dataFim = $data . ' 23:59:59';
        $franquiaLabels = [];
        $qtd = [];
        foreach ($franquias as $franquia) {
            $pedidos = where('id_status', '>=', 3)->where('id_status', '!=', 10)->where('id_franquia', $franquia->id)->whereBetween('created_at', [$dataInicio, $dataFim]);
            $franquiaLabels[] = $franquia->nome_franquia;
            $qtd[] = $pedidos->count();
        }

        return response()->json([
            'labels' => $franquiaLabels,
            'qtd' => $qtd
        ]);
    }


    // Filtrar Entregas Data/Procurar
    public function filtrar_entregas(Request $request, $tipo)
    {
        $procurar = $request->input('procurar');
        $data_inicial = $request->input('data_inicial');
        $data_final = $request->input('data_final');
        $franquia_id = auth()->user()->franquia_id;

        if ($tipo == 'entregas') {
            $query = EntregaPedido::query();

            if (!empty($franquia_id)) {
                $query->where('id_franquia', $franquia_id);
            }

            if (!empty($data_inicial) && !empty($data_final)) {
                $query->whereBetween('data_entrega', [$data_inicial, $data_final]);
            }

            if ($request->has('procurar') && !empty($procurar)) {
                $query->whereHas('pedido.cliente', function ($query) use ($procurar) {
                    $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($procurar) . '%']);
                });
            }

            $entregas = $query->select('id_pedido', 'data_entrega', DB::raw('MAX(id) as id_max'))
                ->orderBy('data_entrega', 'desc')
                ->groupBy('id_pedido', 'data_entrega')
                ->paginate(20);

            return view('admin.dashboard.include._list-entregas', compact('entregas'));
        } elseif ($tipo == 'devolucoes') {
            $query = EntregaPedido::query();

            if (!empty($franquia_id)) {
                $query->where('id_franquia', $franquia_id);
            }

            if (!empty($data_inicial) && !empty($data_final)) {
                $query->whereBetween('data_devolucao', [$data_inicial, $data_final]);
            }

            if ($request->has('procurar') && !empty($procurar)) {
                $query->whereHas('pedido.cliente', function ($query) use ($procurar) {
                    $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($procurar) . '%']);
                });
            }

            $devolucoes = $query->select('id_pedido', 'data_devolucao', DB::raw('MAX(id) as id_max'))
                ->orderBy('data_devolucao', 'desc')
                ->groupBy('id_pedido', 'data_devolucao')
                ->paginate(20);

            return view('admin.dashboard.include._list-devolucoes', compact('devolucoes'));
        }

        return redirect()->route('home');
    }

    // Limpar Filtro
    public function limpar_filtro(Request $request)
    {
        $franquia_id = auth()->user()->franquia_id;

        if (!empty($franquia_id)) {
            $pedidos = Pedidos::where('id_franquia', $franquia_id)->get();
        } else {
            $pedidos = Pedidos::where('id_status', '>=', 3)->where('id_status', '!=', 10)->all();
        }

        return view('admin.dashboard.include._list-entregas', compact('pedidos'));
    }
















    public function buscar($id = null)
    {
        return view('admin.dashboard._dash', compact('id'));
    }


    public function ultimosPagamentos(Request $request, $id = false)
    {

        if (!$id) {

            $id = Auth::user()->id_empresa;
        }

        $pagamentos = Pagamentos::where('id_empresa', $id)->get()->groupBy(function ($q) {
            return $q->created_at->format('d/m/Y');
        })->map(function ($q) {
            return $q->take(100);
        });;

        return view('admin.dashboard.include._ultimos', compact('pagamentos'));
    }

    public function graficoCategorias(Request $request, $id = false)
    {
        if (!$id) {
            $id = Auth::user()->id_empresa;
        }

        $grupos = Grupos::where('id_empresa', $id)->get();
        $dados_grafico = [];

        foreach ($grupos as $grupo) {
            $categorias = 0;
            $categorias = Categorias::where('id_grupo', $grupo->id)->count();
            $dados_grafico[] = [
                'grupos' => $grupo->descricao,
                'categorias' => $categorias,
            ];
        }

        return response()->json($dados_grafico);
    }

    public function graficoProdutos(Request $request, $id = false)
    {
        if (!$id) {
            $id = Auth::user()->id_empresa;
        }

        $categorias = Categorias::where('id_empresa', $id)->get();
        $dados_grafico = [];

        foreach ($categorias as $categoria) {
            $produtos = 0;
            $produtos = Produtos::where('produto_catalogo', 'nao')->where('id_categoria', $categoria->id)->count();
            $dados_grafico[] = [
                'categorias' => $categoria->grupo->descricao . ' - ' . $categoria->descricao,
                'produtos' => $produtos,
            ];
        }

        return response()->json($dados_grafico);
    }

    public function graficoPagamentos(Request $request, $id = false)
    {
        if (!$id) {
            $id = Auth::user()->id_empresa;
        }

        $formas_pagamentos = FormasPagamentos::where('id_empresa', $id)->get();
        $dados_grafico = [];

        foreach ($formas_pagamentos as $forma_pagamento) {
            $pagamentos = 0;
            $pagamentos = Pagamentos::where('id_forma_pagamento', $forma_pagamento->id)->count();
            $dados_grafico[] = [
                'formas_pagamentos' => $forma_pagamento->descricao,
                'pagamentos' => $pagamentos,
            ];
        }

        return response()->json($dados_grafico);
    }

    public function graficoValores(Request $request, $id = false)
    {
        if (!$id) {
            $id = Auth::user()->id_empresa;
        }

        $formas_pagamentos = FormasPagamentos::where('id_empresa', $id)->get();
        $dados_grafico = [];

        foreach ($formas_pagamentos as $forma_pagamento) {
            $pagamentos = 0;
            $valor = 0;
            $pagamentos = Pagamentos::where('id_forma_pagamento', $forma_pagamento->id)->get();

            foreach ($pagamentos as $pagamento) {
                $valor = $valor + $pagamento->valor;
            }

            if ($valor) {
                $dados_grafico[] = [
                    'formas_pagamentos' => $forma_pagamento->descricao,
                    'valores' => $valor,
                ];
            }
        }

        return response()->json($dados_grafico);
    }

    public function getCalendario()
    {
        $itens = Estoque::with('alugueis')->get();

        $eventos = $itens->flatMap(function ($item) {
            return $item->alugueis->map(function ($entrega) use ($item) {
                return [
                    'title' => $item->produto->nome,
                    'start' => $entrega->data_entrega,
                    'end' => date('Y-m-d', strtotime($entrega->data_devolucao . ' +1 day')), // O FullCalendar usa o "end" exclusivo.
                ];
            });
        });

        return response()->json($eventos);
    }

    public function new_calender()
    {
        return view('admin.new-calender');
    }
}
