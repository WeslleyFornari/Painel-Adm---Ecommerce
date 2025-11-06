<?php

namespace App\Http\Controllers\Admin;

use App\Models\BaseConhecimento;
use App\Models\BaseConhecimentoCategorias;
use App\Models\ProdutoCategoria;
use App\Models\Produtos;
use Illuminate\Http\Request;

class BaseConhecimentoController extends Controller
{
    public function index(Request $request)
    {
        $dadosPaginados = BaseConhecimento::paginate(5);
        $selectcategorias = BaseConhecimentoCategorias::select()->distinct()->get();
        return view('admin.base_conhecimento.index', ['bases_conhecimento' => $dadosPaginados, 'selectcategorias' => $selectcategorias]);
    }


    public function store(Request $request){

         $data =  $request->except('_token');
         $base_conhecimento = BaseConhecimento::create([
             'id_categoria' => $data['id_categoria'],
             'titulo' => $data['titulo'],
             'descricao' => $data['descricao'],
             'tipo' => $data['tipo'],
             'status'=> 'ativo',
         ]);
 
       return response()->json(['status'=>'ok'],200);
    }
    public function edit($id)
    {
        $base_conhecimento = BaseConhecimento::find($id);
       
        return response()->json($base_conhecimento);
    }

     public function update(Request $request)   {
            
        $data = $request->except('_token');
    
        BaseConhecimento::where('id',$data['id'])->update([
            'id_categoria' => $data['id_categoria'],
            'descricao' => $data['descricao'],
            'titulo' => $data['titulo'],
            'tipo' => $data['tipo'],
        ]);
    
        return response()->json('editado');
    }

     public function delete(Request $request,$id)   {

        $base_conhecimento = BaseConhecimento::find($id);
        $base_conhecimento-> delete();

        return response()->json(['status'=>'ok']);
    }

    public function mudarStatus($id = null){
        $base_conhecimento = BaseConhecimento::find($id);

        if ($base_conhecimento->status == 'ativo'){
            $base_conhecimento->status = 'inativo';
        }
        else{
            $base_conhecimento->status = 'ativo';
        }

        $base_conhecimento->save();

        return response()->json(['status'=>'ok']);
    }
}
