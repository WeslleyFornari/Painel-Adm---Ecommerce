<?php

namespace App\Http\Controllers\Admin;

use App\Models\BaseConhecimento;
use App\Models\BaseConhecimentoCategorias;
use App\Models\Franquias;
use App\Models\RegiaoAtendida;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegiaoAtendidaController extends Controller
{
    public function cadastro()
    {
        $selecionarFranquia = Franquias::where('status', 'ativo')
            ->distinct()
            ->get();

        return view('admin.regiao_atendida.cadastro', compact('selecionarFranquia'));
    }

    public function index()
    {
        $userRole = Auth::user()->role;
        $userFranquiaId = Auth::user()->id_franquia;

        $query = RegiaoAtendida::query();

        if (Auth::user()->role == 'franqueado') {
    
            $query->where('id_franqueado', $userFranquiaId);
        }

        $regioes = $query->paginate(10);

        return view('admin.regiao_atendida.index', compact('regioes'));
    }

    public function filter(Request $request)
    {
        $userFranquiaId = Auth::user()->id_franquia;

        $query = RegiaoAtendida::query();

        if (Auth::user()->role == 'admin') {

            if ($request->filled('tipo') && $request->input('tipo') != 'todas') {
             
                $query->whereHas('franquia', function ($q) use ($request) {
                    $q->where('tipo_franqueado', $request->input('tipo'));
                }); 
            }

        } elseif (Auth::user()->role == 'franqueado') {
         
            $query->where('id_franqueado', $userFranquiaId);
        }

        if ($request->filled('bairro')) {

            $query->where('bairro', 'like', '%' . $request->input('bairro') . '%');
        }
        if ($request->filled('cidade')) {

            $cidade = trim($request->input('cidade'));
            $query->where('cidade', 'like', '%' . $cidade . '%');
        }
        if ($request->filled('status')) {

            $query->where('status', $request->input('status'));
        }
      
        $regioes = $query->paginate(10);

        if ($request->ajax()) {
            $html = view('admin.regiao_atendida._list', compact('regioes'))->render();
            $pagination = $regioes->links('pagination::bootstrap-4')->render();
      
            return response()->json([
                'html' => $html,
                'pagination' => $pagination, 
            ]);
        }

        return view('admin.regiao_atendida._list', compact('regioes'));
    }


    public function store(Request $request)
    {
        $data =  $request->except('_token');
        $bairro = $data['bairro_selecionado'];
        $cidade = $data['cidade_selecionado'];
        $estado = $data['estado_selecionado'];
        if ($request->filled('valor_entrega_expresso')) {
            $data['valor_entrega_expresso'] = str_replace(',', '.', str_replace('.', '', $data['valor_entrega_expresso']));
        }
        if ($request->filled('valor_entrega_economico')) {
            $data['valor_entrega_economico'] = str_replace(',', '.', str_replace('.', '', $data['valor_entrega_economico']));
        }


        if (Auth::user()->role == 'franqueado') {
            $id_franqueado = Auth::user()->id_franquia;
        } else {
            $id_franqueado = $data['id_franqueado'];
        }

        $regiao_atendida = RegiaoAtendida::create([
            'id_franqueado' => $id_franqueado,
            'bairro' => $bairro ?? null,
            'cidade' => $cidade,
            'estado' => $estado ?? null,
            'tipo' => $data['tipo'],
            'valor_entrega_expresso' => $data['valor_entrega_expresso'] ?? '00.00',
            'valor_entrega_economico' => $data['valor_entrega_economico'] ?? '00.00',
            'tempo_entrega' => $data['tempo_entrega'] ?? 'sem tempo de espera',
            'status' => 'ativo',
        ]);

        return response()->json(['status' => 'ok'], 200);
    }

    public function edit($id)
    {
        $regiao_atendida = RegiaoAtendida::find($id);
        $selecionarFranquia = Franquias::where('status', 'ativo')->select()->distinct()->get();
        return view('admin.regiao_atendida.editar', compact('regiao_atendida', 'selecionarFranquia'));
    }

    public function update(Request $request, $id)
    {

        $data = $request->except('_token');
        // $endereco = explode(",", $data['bairro']);
        // $endereco2 = explode("-", $endereco[1]);

        $bairro = $data['bairro_selecionado'];
        $cidade = $data['cidade_selecionado'];
        $estado = $data['estado_selecionado'];

        if ($request->filled('valor_entrega_expresso')) {
            $data['valor_entrega_expresso'] = str_replace(',', '.', str_replace('.', '', $data['valor_entrega_expresso']));
        }
        if ($request->filled('valor_entrega_economico')) {
            $data['valor_entrega_economico'] = str_replace(',', '.', str_replace('.', '', $data['valor_entrega_economico']));
        }

        if (Auth::user()->role == 'franqueado') {
            $id_franqueado = Auth::user()->id_franquia;
        } else {
            $id_franqueado = $data['id_franqueado'];
        }

        $regiao_atendida = RegiaoAtendida::where('id', $id)->update([
            'id_franqueado' => $id_franqueado,
            'bairro' => $bairro ?? null,
            'cidade' => $cidade,
            'estado' => $estado ?? null,
            'tipo' => $data['tipo'],
            'valor_entrega_expresso' => $data['valor_entrega_expresso'] ?? null,
            'valor_entrega_economico' => $data['valor_entrega_economico'] ?? null,
            'tempo_entrega' => $data['tempo_entrega'],
            'status' => 'ativo',
        ]);

        return response()->json(['status' => 'ok']);
    }



    public function delete(Request $request, $id)
    {

        $regiao_atendida = RegiaoAtendida::find($id);
        $regiao_atendida->delete();

        return response()->json(['status' => 'ok']);
    }



    public function mudarStatus($id = null)
    {
        $regiao_atendida = RegiaoAtendida::find($id);

        if ($regiao_atendida->status == 'ativo') {
            $regiao_atendida->status = 'inativo';
        } else {
            $regiao_atendida->status = 'ativo';
        }

        $regiao_atendida->save();

        return response()->json(['status' => 'ok']);
    }
}
