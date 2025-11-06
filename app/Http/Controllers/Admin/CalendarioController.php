<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BaseStatusPedidos;
use App\Models\EntregaPedido;
use App\Models\FormaPagamento;
use App\Models\ProdutoCategoria;
use App\Models\Franquias;
use App\Models\Pedidos;
use App\Models\Produtos;
use App\Models\Estoque;
use App\Models\RegiaoAtendida;
use App\Models\ItensPedidos;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Dhtmlx\Connector\JSONSchedulerConnector;
use Dhtmlx\Connector\JSONOptionsConnector;

class CalendarioController extends Controller
{
    public function data(Request $request, $id = null, $idProduto = null)
    {
        $res = DB::connection()->getPdo();
        $dbtype = "PDO"; 

        $produtos = new JSONOptionsConnector($res, $dbtype);
        $produtos->render_table("produtos","id","id(value),nome(label)");
        $estoques = new JSONOptionsConnector($res, $dbtype);
        
        // $estoques->render_table("estoques","id","id(value),codigo(label),id_produto(type),status(status)");
        if($id == 'todos' && $idProduto == 'todos'){
            $query = "SELECT id as value, codigo AS label, id_produto AS type, status AS status FROM estoques ORDER BY id_produto";
        }
        else if($id == 'todos' && $idProduto != 'todos'){
            $query = "SELECT id as value, codigo AS label, id_produto AS type, status AS status FROM estoques WHERE id_produto = " . $idProduto;
        }
        else if($idProduto == 'todos' && $id != 'todos'){
            $query = "SELECT id as value, codigo AS label, id_produto AS type, status AS status FROM estoques WHERE id_franqueado = " . $id . " ORDER BY id_produto";
        }
        else{
            $query = "SELECT id as value, codigo AS label, id_produto AS type, status AS status FROM estoques WHERE id_franqueado = " . $id . " and id_produto = ". $idProduto;
        }

        $estoques->render_sql($query, "id", "value,label,type,status");
        
        // $estoques->render_sql($query, "value", "label, type, status");

        $pedidos = new JSONOptionsConnector($res, $dbtype);
        $pedidos->render_table("pedidos","id","id(value),numero(label)");

        $scheduler = new JSONSchedulerConnector($res, $dbtype);

        $scheduler->set_options("produto", $produtos);
        $scheduler->set_options("estoque", $estoques);
        $scheduler->set_options("pedido", $pedidos);

        // $scheduler->render_table("entrega_pedido","id","data_entrega(start_date),data_devolucao(end_date),status(text),id_pedido(pedido),id_item_estoque(estoque)");

         $scheduler->render_table(
            "SELECT ep.id, ep.data_entrega AS start_date, ep.data_devolucao AS end_date, ep.status as text, ep.id_pedido as pedido, ep.id_item_estoque as estoque
             FROM entrega_pedido AS ep
             INNER JOIN pedidos AS p ON ep.id_pedido = p.id
             WHERE p.id_status >= 3 AND p.id_status != 10
             AND ep.data_devolucao IS NOT NULL",
            "id",
            "start_date, end_date, text, pedido, estoque"
        );
    }

    public function index(){
        
        $selecionarFranquia = Franquias::where('status', 'ativo')->select()->distinct()->get();

        if (Auth::user()->role != 'franqueado'){
            $selecionarProduto = Produtos::where('produto_catalogo', 'nao')->select()->distinct()->get();
            $pedidos = Pedidos::where('id_status', '>=', 3)->where('id_status', '!=', 10)->select()->distinct()->get();
            $estoques = Estoque::select()->distinct()->get();
            $produtos = Produtos::where('produto_catalogo', 'nao')->select()->distinct()->get();
        }else{
            $idsProdutos = Estoque::where('id_franqueado', Auth::user()->id_franquia)->pluck('id_produto');
            $selecionarProduto = Produtos::whereIn('id', $idsProdutos)->get();
            $pedidos = Pedidos::where('id_status', '>=', 3)->where('id_status', '!=', 10)->where('id_franquia', Auth::user()->id_franquia)->select()->distinct()->get();
            $estoques = Estoque::where('id_franqueado', Auth::user()->id_franquia)->select()->distinct()->get();
            $produtos = Produtos::whereIn('id', $idsProdutos)->select()->distinct()->get();
        }

        $data_entrega = null;
        $data_devolucao = null;

        return view ('admin.calendario.index', compact('data_devolucao','data_entrega','pedidos', 'estoques', 'produtos','selecionarFranquia', 'selecionarProduto'));
    }

    public function produtos($id){
        $idsProdutos = ItensPedidos::where('id_pedido', $id)->pluck('id_produto');
        $produtos = Produtos::whereIn('id', $idsProdutos)->get();
        return view('admin.calendario._produtos', compact('produtos'));
    }
    public function selectProduto($id){
        $idsProdutos = Estoque::where('id_franqueado', $id)->pluck('id_produto');
        $selecionarProduto = Produtos::whereIn('id', $idsProdutos)->get();
        return view('admin.calendario._selectProdutos', compact('selecionarProduto'));
    }

    public function estoques($id, $id_produto){

        // $estoques = Estoque::where('id_produto', $id)->get();
        $pedido = Pedidos::find($id);
        $item = ItensPedidos::where('id_pedido', $id)->where('id_produto', $id_produto)->first();
        $entrega = EntregaPedido::where('id_itens_pedido', $item->id)->first();
        $produto = Produtos::find($item->id_produto);
        $estoques = Estoque::where('status', 'Disponível')
            ->where('id_produto', $produto->id)
			->where('id_franqueado', $pedido->id_franquia)
            ->whereDoesntHave('alugueis', function ($query) use ($entrega) {
                $startDate = Carbon::parse($entrega->data_entrega);
                $endDate = Carbon::parse($entrega->data_devolucao);
                $query->where(function ($query) use ($startDate, $endDate) {
                    $query->where('data_entrega', '<=', $endDate)
                          ->where('data_devolucao', '>=', $startDate);
                });
            })
            ->get();
        return view('admin.calendario._estoques', compact('estoques'));
    }

    public function datas($id, $id_produto){

        $item = ItensPedidos::where('id_pedido', $id)->where('id_produto', $id_produto)->first();

        $data_entrega = $item->entrega->data_entrega;
        $data_devolucao = $item->entrega->data_devolucao;

        return view('admin.calendario._datas', compact('data_entrega', 'data_devolucao'));
    }

    public function store(Request $request){

        $data =  $request->except('_token');

        $item = ItensPedidos::where('id_pedido', $data['pedido'])->where('id_produto', $data['produto'])->first();

        $item->update([
            'id_item_estoque' => $data['estoque'],
        ]);

        $entrega = $item->entrega;

        $entrega->update([
            'id_item_estoque' => $data['estoque'],
            'data_entrega' => $data['data_entrega'],
            'data_devolucao' => $data['data_devolucao'],
        ]);

        return response()->json(['status'=>'ok']);

    }


}
