<?php

namespace App\Http\Controllers\Admin;

use App\Models\BaseConhecimento;
use App\Models\BaseConhecimentoCategorias;
use Illuminate\Http\Request;

class BaseConhecimentoCategoriasController extends Controller
{
    public function index(Request $request)
    {
        $dadosPaginados = BaseConhecimentoCategorias::paginate(5);
        return view('admin.base_conhecimento.categoria.index', ['base_conhecimento_categorias' => $dadosPaginados]);
    }

    public function store(Request $request){

        $data =  $request->except('_token');
        $baseconhecimentocategoria = BaseConhecimentoCategorias::create([
            'titulo' => $data['titulo'],
            'tipo' => $data['tipo'],
            'status'=> 'ativo',
        ]);

      return response()->json(['status'=>'ok'],200);
   }
   public function edit($id)
    {
        $base_conhecimento_categoria = BaseConhecimentoCategorias::find($id);
       
        return response()->json($base_conhecimento_categoria);
    }

    public function update(Request $request)   {
            
        $data = $request->except('_token');
    
        BaseConhecimentoCategorias::where('id',$data['id'])->update([
            'titulo' => $data['titulo'],
            'tipo' => $data['tipo'],
        ]);
    
        return response()->json('editado');
    }

    public function delete(Request $request,$id)   {

        $base_conhecimento_categoria = BaseConhecimentoCategorias::find($id);
        $base_conhecimento_categoria-> delete();

        return response()->json(['status'=>'ok']);
    }

    public function mudarStatus($id = null){
        $base_conhecimento_categoria = BaseConhecimentoCategorias::find($id);

        if ($base_conhecimento_categoria->status == 'ativo'){
            $base_conhecimento_categoria->status = 'inativo';
        }
        else{
            $base_conhecimento_categoria->status = 'ativo';
        }

        $base_conhecimento_categoria->save();

        return response()->json(['status'=>'ok']);
    }
}
