<?php

namespace App\Http\Controllers\Admin;

use App\Models\BaseConhecimento;
use App\Models\BaseConhecimentoCategorias;
use App\Models\CEPBloqueados;
use App\Models\Franquias;
use App\Models\RegiaoAtendida;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CEPBloqueadosController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->role == 'franqueado'){
            $dadosPaginados = CEPBloqueados::where('id_franquia', Auth::user()->id_franquia)->paginate(5);
        }
        else{
            $dadosPaginados = CEPBloqueados::paginate(5);
        }
        return view('admin.cep_bloqueados.index', ['cep_bloqueados' => $dadosPaginados]);
    }


    public function cadastro(Request $request)
    {
        $selecionarFranquia = Franquias::where('status', 'ativo')->select()->distinct()->get();
        return view('admin.cep_bloqueados.cadastro', compact('selecionarFranquia'));
    }


    public function store(Request $request){

         $data =  $request->except('_token');

        if (Auth::user()->role == 'franqueado'){
            $id_franqueado = Auth::user()->id_franquia;
        }
        else{
            $id_franqueado = $data['id_franqueado'];
        }

        CEPBloqueados::create([
             'id_franquia' => $id_franqueado,
             'cep' =>$data['cep'],
             'endereco' =>$data['endereco'],
             'bairro' => $data['bairro'],
             'cidade' => $data['cidade'],
             'estado' => $data['estado'],
             'pais' => $data['pais'],
             'status'=> 'ativo',
         ]);
 
       return response()->json(['status'=>'ok'],200);
    }



    public function edit($id)
    {
        $cep_bloqueados = CEPBloqueados::find($id);
        $selecionarFranquia = Franquias::where('status', 'ativo')->select()->distinct()->get();
        return view('admin.cep_bloqueados.editar', compact('cep_bloqueados','selecionarFranquia'));
    }



     public function update(Request $request, $id)   {
            
        $data = $request->except('_token');
        if (Auth::user()->role == 'franqueado'){
            $id_franqueado = Auth::user()->id_franquia;
        }
        else{
            $id_franqueado = $data['id_franqueado'];
        }
    
        CEPBloqueados::where('id',$id)->update([
             'id_franquia' => $id_franqueado,
             'cep' =>$data['cep'],
             'endereco' =>$data['endereco'],
             'bairro' => $data['bairro'],
             'cidade' => $data['cidade'],
             'estado' => $data['estado'],
             'pais' => $data['pais'],
         ]);
    
         return response()->json(['status'=>'ok']);
    }



     public function delete(Request $request,$id)   {

        CEPBloqueados::find($id)->delete();

        return response()->json(['status'=>'ok']);
    }



    public function mudarStatus($id = null){
        $cep_bloqueados = CEPBloqueados::find($id);

        if ($cep_bloqueados->status == 'ativo'){
            $cep_bloqueados->status = 'inativo';
        }
        else{
            $cep_bloqueados->status = 'ativo';
        }

        $cep_bloqueados->save();

        return response()->json(['status'=>'ok']);
    }
}
