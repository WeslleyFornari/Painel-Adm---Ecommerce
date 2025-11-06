<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProdutoCategoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Queue\Failed\NullFailedJobProvider;
use League\CommonMark\Extension\SmartPunct\EllipsesParser;

class ProdutosCategoriasController extends Controller
{
    public function index(Request $request)
    {

        if(Auth::user()->role != 'franqueado'){
            $dadosPaginados = ProdutoCategoria::paginate(5);
            $selectcategorias = ProdutoCategoria::select()->distinct()->get();
        }
        else{
            $dadosPaginados = ProdutoCategoria::where('tipo', Auth::user()->franquia->tipo_franqueado)->paginate(5);
            $selectcategorias = ProdutoCategoria::where('tipo', Auth::user()->franquia->tipo_franqueado)->select()->distinct()->get();
        }
        return view('admin.produtos.produtos_categorias.index', ['produtos_categorias' => $dadosPaginados, 'selectcategorias' => $selectcategorias]);
    }

    public function new(){
   
        return view('admin.produtos.produtos_categorias.new');
    }

    public function store(Request $request){

         $data =  $request->except('_token');
       
         $slug = Str::slug($data['nome'], '-');

         $produto_categoria = ProdutoCategoria::create([
             'nome' => $data['nome'],
             'descricao' => $data['descricao'],
             'id_parent' => $data['id_parent'],
             'id_media' => $data['id_media'],
             'tipo' => $data['tipo'],
             'status'=> 'ativo',
             'slug'=> 'teste',
         ]);

         $slug = Str::slug($data['nome'], '-') . '-' . $produto_categoria->id;
        $produto_categoria->update([
            'slug' => $slug,
        ]);
       return response()->json(['status'=>'ok'],200);
     }

    public function edit($id)
    {
        $produto_categoria = ProdutoCategoria::find($id);
       
        return view('admin.produtos.produtos_categorias.edit', compact('produto_categoria'));
    }

     public function update(Request $request)   {
            
        $data=$request->except('_token');
      //  dd($data);
        $id = $data['id']; 
        $slug = Str::slug($data['nome'], '-');

        ProdutoCategoria::where('id',$id)->update([
            'nome' => $data['nome'],
            'descricao' => $data['descricao'],
            'id_parent' => $data['id_parent'],
            'id_media' => $data['id_media'],
            'tipo' => $data['tipo'],
            'slug'=> "$slug-$id",
        ]);
    
        return response()->json('editado');
    }

     public function delete(Request $request,$id)   {

        $produto_categoria = ProdutoCategoria::find($id);
        $produto_categoria-> delete();

        return response()->json(['status'=>'ok']);
    }

    public function mudarStatus($id = null){
        $produto_categoria = ProdutoCategoria::find($id);

        

        if ($produto_categoria->status == 'ativo'){
            $produto_categoria->status = 'inativo';
        }
        else{
            $produto_categoria->status = 'ativo';
        }

        $produto_categoria->save();

        return response()->json(['status'=>'ok']);
    }
}
