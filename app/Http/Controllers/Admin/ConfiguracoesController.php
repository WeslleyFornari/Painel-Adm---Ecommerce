<?php

namespace App\Http\Controllers\Admin;

use App\Models\Configuracoes;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ConfiguracoesController extends Controller
{
    
    public function index(Request $request){

     
        $configuracoes = Configuracoes::paginate(5);
    
        return view('admin.configuracoes.index', compact('configuracoes'));
    }


// STORE
    public function store(Request $request){

        $data = $request->all();

        $id_franquia = Auth::User()->id_franquia;
        
        $parametro = Str::slug($data['titulo'], '-');
        
        Configuracoes::create([

            'id_franqueado' => $id_franquia,
            'tipo_franqueado'=>$data['tipo_franqueado'],
            'titulo'=>$data['titulo'],
            'param'=>$parametro,
            'value'=>$data['value']

        ]);

        $configuracoes = Configuracoes::paginate(5);

        return view('admin.configuracoes._list', compact('configuracoes'));
    }

// EDIT
    public function edit(Request $request, $id){
                
        $configuracao = Configuracoes::find($id);
       
        return view('admin.configuracoes.cadastrar', compact('configuracao'));

    }

//UPDATE   
    public function update(Request $request,$id)   {
                
        $data=$request->except('_token'); 

        $id_franquia = Auth::User()->id_franquia;
        $parametro = Str::slug($data['titulo'], '-');

        Configuracoes::where('id',$id)->update([
            
            'id_franqueado' => $id_franquia,
            'tipo_franqueado'=>$data['tipo_franqueado'],
            'titulo'=>$data['titulo'],
            'param'=>$parametro,
            'value'=>$data['value']
        ]);

        $configuracoes = Configuracoes::paginate(5);
    
        return view('admin.configuracoes._list', compact('configuracoes'));
    }

// DELETAR
    public function delete(Request $request,$id)   {

        $configuracao = Configuracoes::find($id);
        $configuracao-> delete();

        $configuracoes = Configuracoes::paginate(5);
        
        return view('admin.configuracoes._list', compact('configuracoes'));
    }


}
