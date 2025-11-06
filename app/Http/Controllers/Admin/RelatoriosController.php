<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BaseStatusPedidos;
use App\Models\EntregaPedido;
use App\Models\Estoque;
use App\Models\FormaPagamento;
use App\Models\ProdutoCategoria;
use App\Models\Franquias;
use App\Models\Pedidos;
use App\Models\Produtos;
use App\Models\RegiaoAtendida;
use App\Models\ItensPedidos;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Dhtmlx\Connector\JSONSchedulerConnector;
use Dhtmlx\Connector\JSONOptionsConnector;
use Illuminate\Support\Collection;

class RelatoriosController extends Controller
{

    public function index(Request $request)
    {
        if (Auth::user()->role == 'franqueado') {

            $pedidos = Pedidos::where('id_franquia', Auth::user()->id_franquia)->get();

            $clientesComPedidos = Pedidos::where('id_franquia', Auth::user()->id_franquia)
                ->pluck('id_cliente');

            $clientes = User::where('role', 'user')
                ->whereIn('id', $clientesComPedidos)
                ->get();

            $produtos = Produtos::where('produto_catalogo', 'nao')->orderBy('nome', 'ASC')->get();

            $regioes = RegiaoAtendida::where('id_franqueado', Auth::user()->id_franquia)->get();

            $formas_pagamento = FormaPagamento::all();
        } else {

            $pedidos = Pedidos::orderBy('created_at', 'asc')->get();

            $clientes = User::where('role', 'user')->get();

            $produtos = Produtos::where('produto_catalogo', 'nao')->orderBy('nome', 'ASC')->get();

            $regioes = RegiaoAtendida::all();

            $formas_pagamento = FormaPagamento::all();
        }

        $status = BaseStatusPedidos::where('status', 'ativo')->get();
        $franquias = Franquias::where('status', 'ativo')->get();

        return view('admin.relatorios.menu', compact('pedidos', 'clientes', 'status', 'produtos', 'regioes', 'formas_pagamento', 'franquias'));
    }



    public function pedidosSemPagamento(Request $request)
    {
        $franquia = $request->input('franquia');
        $query = Pedidos::query();

        if (Auth::user()->role == 'franqueado') {

            $query->where('id_franquia', Auth::user()->id_franquia)->where('id_status', '2');
        } else {

            $query->where('id_status', '2')->get();

            if ($franquia == 'trip') {

                $query->whereHas('franquia', function ($q) {
                    $q->where('tipo_franqueado', 'trip');
                });
            } elseif ($franquia == 'toy') {

                $query->whereHas('franquia', function ($q) {
                    $q->where('tipo_franqueado', 'toy');
                });
            }
        }

        $pedidos = $query->orderBy('created_at', 'desc')->orderBy('created_at', 'desc')->paginate(10);

        return response()->json([
            'titulo' => 'Pedidos sem pagamento',
            'html' => view('admin.relatorios.pedidos.sem_pagamento', compact('pedidos'))->render(),
        ]);
    }

    public function pedidosCliente(Request $request)
    {
        $data = $request->except('_token');
        $pedidos = Pedidos::query();

        if (Auth::user()->role == 'franqueado') {
            $pedidos->where('id_franquia', Auth::user()->id_franquia)->orderBy('created_at', 'desc')->get();
        } else {
            $pedidos = $pedidos;
        }
        $pedidos->where('id_cliente', $data['cliente']);

        if ($data['pedidos'] !== 'todos') {
            $pedidos->where('id_status', '!=', 9);
        }
        $pedidos_todos = $pedidos->get();

        $pedidos = $pedidos->orderBy('created_at', 'desc')->paginate(10);

        // $cliente = User::find($data['cliente']);
        $categorias = ProdutoCategoria::all();

        return response()->json([

            'titulo' => 'Relatório de Pedidos por Cliente',
            'html' => view('admin.relatorios.pedidos.por_cliente', compact('pedidos', 'pedidos_todos', 'categorias'))->render(),
        ]);
    }

    public function pedidosFiltro(Request $request)
    {
        $data = $request->except('_token');
        $franquia = $request->input('franquia');
        $query = Pedidos::query();

        if (Auth::user()->role == 'franqueado') {
            $query->where('id_franquia', Auth::user()->id_franquia);
        } else {
            if ($franquia == 'trip') {
                $query->whereHas('franquia', function ($q) {
                    $q->where('tipo_franqueado', 'trip');
                });
            } elseif ($franquia == 'toy') {
                $query->whereHas('franquia', function ($q) {
                    $q->where('tipo_franqueado', 'toy');
                });
            }
        }

        if ($data['cliente'] != 'todos') {
            $query->where('id_cliente', $data['cliente']); 
        } else {
            $query->where('id_cliente', '!=', null); 
        }

        if ($data['produto'] != 'todos') {
            $query->whereHas('itens', function ($q) use ($data) {
                $q->where('id_produto', $data['produto']);
            });
        }
        if ($request->has(key: 'situacao') && !empty($data['situacao'])) {
            $query->whereIn('id_status', $data['situacao']);
        }

        if ($request->filled('data_inicio') && $request->filled('data_fim')) {
            $startDate = $data['data_inicio']; 
            $endDate = $data['data_fim'];

        } elseif ($request->filled('meses') && $request->filled('ano')) {
            $mes = $data['meses'];
            $ano = $data['ano'];
            $startDate = \Carbon\Carbon::create($ano, $mes, 1)->startOfMonth();
            $endDate = \Carbon\Carbon::create($ano, $mes, 1)->endOfMonth();

        } elseif ($request->filled('recente')) {

            $today = \Carbon\Carbon::today(); 

            if ($data['recente'] == 'hoje') {
              
                $startDate = $today->startOfDay()->format('Y-m-d H:i:s');
                $endDate = $today->endOfDay()->format('Y-m-d H:i:s');

            } elseif ($data['recente'] == 'esta_semana') {
                $startDate = $today->startOfWeek()->format('Y-m-d');
                $endDate = $today->endOfWeek()->format('Y-m-d');
            }
        }

        $query->whereBetween('created_at', [$startDate, $endDate]);

        $pedidos = $query->orderBy('created_at', 'desc')->paginate(10);

        return response()->json([
            'titulo' => 'Relatório de Pedidos',
            'html' => view('admin.relatorios.pedidos.todos_pedidos', compact('pedidos'))->render(),
        ]);
    }


    public function clientesAll(Request $request)
    {
        $clientes = User::query();

        if (Auth::user()->role == 'franqueado') {

            $clientes->where('id_franquia', Auth::user()->id_franquia)->get();
        } else {

            $clientes->where('role', 'user')->get();
        }

        $clientes->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.relatorios.clientes.lista-clientes', compact('clientes'));
    }


    public function itemLogistica(Request $request)
    {
        $data = $request->except('_token');
        $franquia = $request->input('franquia');
        $query = EntregaPedido::query();

        if (Auth::user()->role == 'franqueado') {

            $query->whereHas('pedido', function ($q) {
                $q->where('id_franquia', Auth::user()->id_franquia);
            });
        } else {

            if ($franquia == 'trip') {

                $query->whereHas('pedido.franquia', function ($q) {
                    $q->where('tipo_franqueado', 'trip');
                });
            } elseif ($franquia == 'toy') {

                $query->whereHas('pedido.franquia', function ($q) {
                    $q->where('tipo_franqueado', 'toy');
                });
            }
        }

        if ($request->filled('data_inicio') && $request->filled('data_fim')) {
            $startDate = $data['data_inicio'];
            $endDate = $data['data_fim'];
        } elseif ($request->filled('meses') && $request->filled('ano')) {
            $mes = $data['meses'];
            $ano = $data['ano'];
            $startDate = \Carbon\Carbon::create($ano, $mes, 1)->startOfMonth();
            $endDate = \Carbon\Carbon::create($ano, $mes, 1)->endOfMonth();
        } elseif ($request->filled('recente')) {
            $today = \Carbon\Carbon::today();
            if ($data['recente'] == 'hoje') {
                $startDate = $today;
                $endDate = $today;
            } elseif ($data['recente'] == 'esta_semana') {
                $startDate = $today->startOfWeek();
                $endDate = $today->endOfWeek();
            }
        }

        if ($request->filled('tipo_logistica')) {
            if ($data['tipo_logistica'] == 'frete') {
                $query->whereHas('pedido', function ($q) {
                    $q->where('tipo_frete', '!=', 'retirar_loja');
                });
            } elseif ($data['tipo_logistica'] == 'retirar_loja') {
                $query->whereHas('pedido', function ($q) {
                    $q->where('tipo_frete', 'retirar_loja');
                });
            }
        }

        if ($request->filled('tipo_movimentacao')) {
            if ($data['tipo_movimentacao'] == 'todos') {
                $query->where(function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('data_entrega', [$startDate, $endDate])
                        ->orWhereBetween('data_devolucao', [$startDate, $endDate]);
                });
            } elseif ($data['tipo_movimentacao'] == 'entrega') {
                $query->whereBetween('data_entrega', [$startDate, $endDate]);
            } elseif ($data['tipo_movimentacao'] == 'devolucao') {
                $query->whereBetween('data_devolucao', [$startDate, $endDate]);
            }
        }

        $entregas = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return response()->json([

            'titulo' => 'Pedidos',
            'html' => view('admin.relatorios.logistica.relatorio_item_logistica', compact('entregas'))->render(),
        ]);
    }

    public function Logistica(Request $request)
    {
        $data = $request->except('_token');
        $franquia = $request->input('franquia');
        $query = EntregaPedido::query();

        if (Auth::user()->role == 'franqueado') {
            $query->whereHas('pedido', function ($q) {
                $q->where('id_franquia', Auth::user()->id_franquia);
            });
        } else {

            if ($franquia == 'trip') {

                $query->whereHas('pedido.franquia', function ($q) {
                    $q->where('tipo_franqueado', 'trip');
                });
            } elseif ($franquia == 'toy') {

                $query->whereHas('pedido.franquia', function ($q) {
                    $q->where('tipo_franqueado', 'toy');
                });
            }
        }

        if ($request->filled('data_inicio') && $request->filled('data_fim')) {
            $startDate = $data['data_inicio'];
            $endDate = $data['data_fim'];
        } elseif ($request->filled('meses') && $request->filled('ano')) {
            $mes = $data['meses'];
            $ano = $data['ano'];
            $startDate = \Carbon\Carbon::create($ano, $mes, 1)->startOfMonth();
            $endDate = \Carbon\Carbon::create($ano, $mes, 1)->endOfMonth();
        } elseif ($request->filled('recente')) {
            $today = \Carbon\Carbon::today();

            if ($data['recente'] == 'hoje') {
                $startDate = $today;
                $endDate = $today;
            } elseif ($data['recente'] == 'esta_semana') {
                $startDate = $today->startOfWeek();
                $endDate = $today->endOfWeek();
            }
        }

        if ($request->filled('tipo_logistica')) {
            if ($data['tipo_logistica'] == 'frete') {
                $query->whereHas('pedido', function ($q) {
                    $q->where('tipo_frete', '!=', 'retirar_loja');
                });
            } elseif ($data['tipo_logistica'] == 'retirar_loja') {
                $query->whereHas('pedido', function ($q) {
                    $q->where('tipo_frete', 'retirar_loja');
                });
            }
        }

        if ($request->filled('tipo_movimentacao')) {
            if ($data['tipo_movimentacao'] == 'todos') {
                $query->where(function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('data_entrega', [$startDate, $endDate])
                        ->orWhereBetween('data_devolucao', [$startDate, $endDate]);
                });
            } elseif ($data['tipo_movimentacao'] == 'entrega') {
                $query->whereBetween('data_entrega', [$startDate, $endDate]);
            } elseif ($data['tipo_movimentacao'] == 'devolucao') {
                $query->whereBetween('data_devolucao', [$startDate, $endDate]);
            }
        }
        if ($request->filled('local')) {
            $regiao = RegiaoAtendida::find($data['local']);
            if ($regiao) {
                $query->whereHas('pedido', function ($q) use ($regiao) {
                    $bairro = str_replace('Loteamento ', '', $regiao->bairro);
                    $bairro = '%' . $bairro . '%';
                    $q->whereHas('endereco_entrega', function ($r) use ($bairro) {
                        $r->where('bairro', 'like', $bairro);
                    });
                });
            }
        }

        $entregas = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        return response()->json([

            'titulo' => 'Relatório de Logistica',
            'html' => view('admin.relatorios.logistica.relatorio_logistica', compact('entregas'))->render(),
        ]);
    }

    public function financeiro(Request $request)
    {
        $data = $request->except('_token');
        $franquia = $request->input('franquia');
        $query = Pedidos::query();

        if (Auth::user()->role == 'franqueado') {
            $query->where('id_franquia', Auth::user()->id_franquia);
        } else {

            if ($franquia == 'trip') {

                $query->whereHas('franquia', function ($q) {
                    $q->where('tipo_franqueado', 'trip');
                });
            } elseif ($franquia == 'toy') {

                $query->whereHas('franquia', function ($q) {
                    $q->where('tipo_franqueado', 'toy');
                });
            }
        }

        if ($request->filled('data_inicio') && $request->filled('data_fim')) {
            $startDate = $data['data_inicio'];
            $endDate = $data['data_fim'];
        } elseif ($request->filled('meses') && $request->filled('ano')) {
            $mes = $data['meses'];
            $ano = $data['ano'];
            $startDate = \Carbon\Carbon::create($ano, $mes, 1)->startOfMonth();
            $endDate = \Carbon\Carbon::create($ano, $mes, 1)->endOfMonth();
        } elseif ($request->filled('recente')) {
            $today = \Carbon\Carbon::today();

            if ($data['recente'] == 'hoje') {
                $startDate = $today;
                $endDate = $today;
            } elseif ($data['recente'] == 'essa_semana') {
                $startDate = $today->startOfWeek();
                $endDate = $today->endOfWeek();
            }
        }

        $query->whereBetween('created_at', [$startDate, $endDate]);

        if ($request->filled('situacao_financeiro')) {
            if ($data['situacao_financeiro'] == 'pendentes') {
                $query->where('id_status', '2');
            } elseif ($data['situacao_financeiro'] == 'pagos') {
                $query->where('id_status', '>=', '3');
                $query->where('id_status', '!=', '10');
            }
        }
        if ($request->filled('forma_pagamento')) {
            if ($data['forma_pagamento'] != 'todos') {
                $query->where('id_forma_pagamento', $data['forma_pagamento']);
            }
        }
        if ($request->filled('clientes')) {
            if ($data['clientes'] != 'todos') {
                $query->where('id_cliente', $data['clientes']);
            }
        }

        $pedidos = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        return response()->json([

            'titulo' => 'Relatório de Logistica',
            'html' => view('admin.relatorios.financeiro.relatorios_transacoes_financeiras', compact('pedidos', 'startDate', 'endDate'))->render(),
        ]);
    }

    public function pagamentos(Request $request)
    {
        $data = $request->except('_token');
        $franquia = $request->input('franquia');
        $query = Pedidos::query();

        if (Auth::user()->role == 'franqueado') {
            $query->where('id_franquia', Auth::user()->id_franquia);
        } else {

            if ($franquia == 'trip') {

                $query->whereHas('franquia', function ($q) {
                    $q->where('tipo_franqueado', 'trip');
                });
            } elseif ($franquia == 'toy') {

                $query->whereHas('franquia', function ($q) {
                    $q->where('tipo_franqueado', 'toy');
                });
            }
        }

        if ($request->filled('data_inicio') && $request->filled('data_fim')) {
            $startDate = $data['data_inicio'];
            $endDate = $data['data_fim'];
        } elseif ($request->filled('meses') && $request->filled('ano')) {
            $mes = $data['meses'];
            $ano = $data['ano'];
            $startDate = \Carbon\Carbon::create($ano, $mes, 1)->startOfMonth();
            $endDate = \Carbon\Carbon::create($ano, $mes, 1)->endOfMonth();
        } elseif ($request->filled('recente')) {
            $today = \Carbon\Carbon::today();
            if ($data['recente'] == 'hoje') {
                $startDate = $today;
                $endDate = $today;
            } elseif ($data['recente'] == 'essa_semana') {
                $startDate = $today->startOfWeek();
                $endDate = $today->endOfWeek();
            }
        }

        $query->whereBetween('created_at', [$startDate, $endDate]);



        if ($request->filled('situacao_financeiro')) {
            if ($data['situacao_financeiro'] == 'pendentes') {
                $query->where('id_status', '2');
            } elseif ($data['situacao_financeiro'] == 'pagos') {
                $query->where('id_status', '>=', '3');
                $query->where('id_status', '!=', '10');
            }
        }
        if ($request->filled('forma_pagamento')) {
            if ($data['forma_pagamento'] != 'todos') {
                $query->where('id_forma_pagamento', $data['forma_pagamento']);
            }
        }
        if ($request->filled('clientes')) {
            if ($data['clientes'] != 'todos') {
                $query->where('id_cliente', $data['clientes']);
            }
        }

        $pedidos = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $pedidosPendentes = $query->orderBy('created_at', 'desc')->where('id_status', '<=', '2')->paginate(10)->withQueryString();
        $pedidosRealizados = $query->orderBy('created_at', 'desc')->where('id_status', '>=', '3')->where('id_status', '!=', 10)->paginate(10)->withQueryString();

        return response()->json([

            'titulo' => 'Pagamentos dos pedidos',
            'html' => view('admin.relatorios.financeiro.relatorios_pagamentos_pedidos', compact('pedidos', 'startDate', 'endDate', 'pedidosPendentes', 'pedidosRealizados'))->render(),
        ]);
    }

    public function clientes(Request $request)
    {
        $data = $request->except('_token');
        $query = User::query();

        if (Auth::user()->role == 'franqueado') {

            $query->where('id_franquia', Auth::user()->id_franquia)->get();
        } else {

            $query->where('role', 'user')->get();
        }

        if ($request->filled('palavra')) {
            $palavra = '%' . $data['palavra'] . '%';
            $query->where('name', 'like', $palavra);
        }

        if ($request->filled('pessoa')) {
            if ($data['pessoa'] == 'fisica') {
                $query->where('cnpj', '');
            } elseif ($data['pessoa'] == 'juridico') {
                $query->where('cnpj', '!=', '');
            }
        }
        $dados = $request->input('dados', []);
        $clientes = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return response()->json([

            'titulo' => 'Relatório de todos os clientes',
            'html' => view('admin.relatorios.clientes.relatorios_clientes', compact('clientes', 'dados'))->render(),
        ]);
    }

    public function ClientesMaisPedidos(Request $request)
    {
        $data = $request->except('_token');
        $pedidos = Pedidos::query();

        if (Auth::user()->role == 'franqueado') {

            $pedidos->where('pedidos.id_franquia', Auth::user()->id_franquia);
        } else {

            $pedidos = $pedidos;
        }

        $startDate = null;
        $endDate = null;

        if ($request->filled('meses') && $request->filled('ano')) {
            $ano = $data['ano'];
            if ($data['meses'] == 'todos') {
                $startDate = \Carbon\Carbon::create($ano, 1, 1)->startOfDay();
                $endDate = \Carbon\Carbon::create($ano, 12, 31)->endOfDay();
            } else {
                $mes = $data['meses'];
                $startDate = \Carbon\Carbon::create($ano, $mes, 1)->startOfMonth();
                $endDate = \Carbon\Carbon::create($ano, $mes, 1)->endOfMonth();
            }
        }
        if ($startDate && $endDate) {
            $clientes = $pedidos->select('id_cliente', DB::raw('COUNT(*) as qtd_pedidos'))
                ->join('users', 'users.id', '=', 'pedidos.id_cliente') // Faz o join com a tabela clientes
                ->whereNotNull('users.name') // Exclui clientes com nome nulo
                ->whereBetween('pedidos.created_at', [$startDate, $endDate])
                ->groupBy('id_cliente')
                ->orderBy('qtd_pedidos', 'DESC')
                ->paginate(10);
        }

        return response()->json([

            'titulo' => 'Clientes com mais pedidos',
            'html' => view('admin.relatorios.clientes.relatorios_mais_pedidos', compact('clientes'))->render(),
        ]);
    }


    public function ClientesMaisGastam(Request $request)
    {
        $data = $request->except('_token');
        $pedidos = Pedidos::query();

        if (Auth::user()->role == 'franqueado') {
            $pedidos->where('pedidos.id_franquia', Auth::user()->id_franquia);
        } else {
            $pedidos = $pedidos;;
        }

        $startDate = null;
        $endDate = null;

        if ($request->filled('meses') && $request->filled('ano')) {
            $ano = $data['ano'];
            if ($data['meses'] == 'todos') {
                $startDate = \Carbon\Carbon::create($ano, 1, 1)->startOfDay();
                $endDate = \Carbon\Carbon::create($ano, 12, 31)->endOfDay();
            } else {
                $mes = $data['meses'];
                $startDate = \Carbon\Carbon::create($ano, $mes, 1)->startOfMonth();
                $endDate = \Carbon\Carbon::create($ano, $mes, 1)->endOfMonth();
            }
        }
        if ($startDate && $endDate) {
            $clientes = $pedidos->select('id_cliente', DB::raw('SUM(valor_total) as total_gasto'))
                ->join('users', 'users.id', '=', 'pedidos.id_cliente') // Faz o join com a tabela clientes
                ->whereNotNull('users.name') // Exclui clientes com nome nulo
                ->groupBy('id_cliente')
                ->orderBy('total_gasto', 'DESC')
                ->whereBetween('pedidos.created_at', [$startDate, $endDate])
                ->paginate(10);
        }
        return response()->json([

            'titulo' => 'Clientes que mais gastaram em valor',
            'html' => view('admin.relatorios.clientes._relatorios_mais_gastam', compact('clientes'))->render(),
        ]);
    }


    public function data(Request $request, $id = null, $idProduto = null)
    {
        $res = DB::connection()->getPdo();
        $dbtype = "PDO";

        $produtos = new JSONOptionsConnector($res, $dbtype);
        $produtos->render_table("produtos", "id", "id(value),nome(label)");
        $estoques = new JSONOptionsConnector($res, $dbtype);

        // $estoques->render_table("estoques","id","id(value),codigo(label),id_produto(type),status(status)");
        if ($id == 'todos' && $idProduto == 'todos') {
            $query = "SELECT id as value, codigo AS label, id_produto AS type, status AS status FROM estoques ORDER BY id_produto";
        } else if ($id == 'todos' && $idProduto != 'todos') {
            $query = "SELECT id as value, codigo AS label, id_produto AS type, status AS status FROM estoques WHERE id_produto = " . $idProduto;
        } else if ($idProduto == 'todos' && $id != 'todos') {
            $query = "SELECT id as value, codigo AS label, id_produto AS type, status AS status FROM estoques WHERE id_franqueado = " . $id . " ORDER BY id_produto";
        } else {
            $query = "SELECT id as value, codigo AS label, id_produto AS type, status AS status FROM estoques WHERE id_franqueado = " . $id . " and id_produto = " . $idProduto;
        }

        $estoques->render_sql($query, "id", "value,label,type,status");

        // $estoques->render_sql($query, "value", "label, type, status");

        $pedidos = new JSONOptionsConnector($res, $dbtype);
        $pedidos->render_table("pedidos", "id", "id(value),numero(label)");

        $scheduler = new JSONSchedulerConnector($res, $dbtype);

        $scheduler->set_options("produto", $produtos);
        $scheduler->set_options("estoque", $estoques);
        $scheduler->set_options("pedido", $pedidos);

        $scheduler->render_table("entrega_pedido", "id", "data_entrega(start_date),data_devolucao(end_date),status(text),id_pedido(pedido),id_item_estoque(estoque)");
    }
    public function calendario()
    {
        $selecionarFranquia = Franquias::where('status', 'ativo')->select()->distinct()->get();
        $selecionarProduto = Produtos::where('status', 'ativo')->select()->distinct()->get();
        return view('admin.calendario', compact('selecionarFranquia', 'selecionarProduto'));
    }

    public function curvaABC(Request $request)
    {
        $data = $request->except('_token');
        $user = Auth::user();
        $franquia = $request->input('franquia');
        $query = Pedidos::query();

        if ($user->role == 'franqueado') {
            $query->where('id_franquia', $user->id_franquia);
        } else {
            if ($franquia == 'trip') {
                $query->whereHas('franquia', function ($q) {
                    $q->where('tipo_franqueado', 'trip');
                });
            } elseif ($franquia == 'toy') {
                $query->whereHas('franquia', function ($q) {
                    $q->where('tipo_franqueado', 'toy');
                });
            }
        }

        $startDate = null;
        $endDate = null;

        if ($request->filled('meses') && $request->filled('ano')) {
            $ano = $data['ano'];
            if ($data['meses'] == 'todos') {
                $startDate = \Carbon\Carbon::create($ano, 1, 1)->startOfDay();
                $endDate = \Carbon\Carbon::create($ano, 12, 31)->endOfDay();
            } else {
                $mes = $data['meses'];
                $startDate = \Carbon\Carbon::create($ano, $mes, 1)->startOfMonth();
                $endDate = \Carbon\Carbon::create($ano, $mes, 1)->endOfMonth();
            }
        }
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        $produtos = ItensPedidos::select(
            'id_produto',
            \DB::raw('COUNT(DISTINCT id_pedido) as num_pedidos'),
            \DB::raw('SUM(qtd) as qtd_faturada'),
            \DB::raw('AVG(valor_unitario) as preco_unitario_medio'),
            \DB::raw('SUM(valor_total) as receita_total_bruta')
        )
            ->whereIn('id_pedido', $query->pluck('id'))
            ->groupBy('id_produto')
            ->orderByDesc('receita_total_bruta')
            ->get();

        $valorTotalVendasBrutas = $produtos->sum('receita_total_bruta');
        $acumulado = 0;
        $produtosClassificados = $produtos->map(function ($produto) use (&$acumulado, $valorTotalVendasBrutas) {
            $produto->receita_total_liquida = $produto->receita_total_bruta;
            $produto->participacao = ($produto->receita_total_bruta / $valorTotalVendasBrutas) * 100;
            $acumulado += $produto->participacao;
            $produto->classificacao = $acumulado <= 80 ? 'A' : ($acumulado <= 95 ? 'B' : 'C');

            return $produto;
        });

        return response()->json([

            'titulo' => 'RELATÓRIO DE ESTOQUE - CURVA ABC (DE ' . $startDate->format('Y-m-d') . ' ATÉ ' . $endDate->format('Y-m-d') . ')',
            'html' => view('admin.relatorios.itens.curva_abc', compact('produtosClassificados', 'startDate', 'endDate'))->render(),
        ]);
    }
    public function itensEstoque(Request $request)
    {
        $data = $request->except('_token');
        $user = Auth::user();
        $franquia = $request->input('franquia');
        $query = Estoque::query();
        
        $titulo = 'RELATÓRIO DE ESTOQUE - LISTAGEM DE PATRIMONIOS';
        
        $filtroInfo = [];
        
        if ($user->role == 'franqueado') {
            $query->where('id_franquia', $user->id_franquia);
            $franquiaInfo = Franquias::find($user->id_franquia);
            if ($franquiaInfo) {
                $filtroInfo[] = "Franquia: {$franquiaInfo->nome_franquia}";
            }
        } else {
            if($franquia != 'todas'){
                $query->where('id_franqueado', $franquia);
                $franquiaInfo = Franquias::find($franquia);
                if ($franquiaInfo) {
                    $filtroInfo[] = "Franquia: {$franquiaInfo->nome_franquia}";
                }
            }
            
            if($request->input('produto') != 'todos'){
                $produtoId = $request->input('produto');
                $query->where('id_produto', $produtoId);
                $produtoInfo = Produtos::find($produtoId);
                if ($produtoInfo) {
                    $filtroInfo[] = "Produto: {$produtoInfo->nome}";
                }
            }
        }
        
        if (!empty($filtroInfo)) {
            $titulo .= ' - ' . implode(' | ', $filtroInfo);
        }
        
        $estoque = $query->with('produto')->get();

        return response()->json([
            'titulo' => $titulo,
            'html' => view('admin.relatorios.estoque.lista-estoque', compact('estoque'))->render(),
        ]);
    }
}
