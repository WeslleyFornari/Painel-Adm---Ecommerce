<?php

namespace App\Http\Controllers\Admin;

use App\Models\Depoimentos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepoimentosController extends Controller
{
    

// LISTA
    public function index(Request $request){
        
        $depoimentos = Depoimentos::paginate(5);

        return view('admin.depoimentos.index', compact('depoimentos'));
    }

// NEW
    public function new(){
   
        return view('admin.depoimentos.new');
    }


// STORE
    public function store(Request $request){

        $data =  $request->except('_token');
  
        $status = isset($data['status']) ? $data['status'] : 'inativo'; 
          
          $depoimento = new Depoimentos();
  
          $depoimento->id_foto = $request->input('id_foto');
          $depoimento->texto = $request->input('texto');
          $depoimento->tipo_franqueado = $request->input('tipo_franqueado');         
          $depoimento->status = $status;
  
          $depoimento->save();
  
          return response()->json([
              'status'=>'Salvo com sucesso',
          ]);
      }

// EDIT
    public function edit(Request $require,$id){

        $depoimento = Depoimentos::find($id);
        
        return view('admin.depoimentos.edit',compact('depoimento'));
    }

    
// UPDATE
    public function update(Request $request,$id){

        $data =  $request->except('_token');

        $depoimento = Depoimentos::find($id);

        $status = isset($data['status']) ? $data['status'] : 'inativo'; 
        
        $depoimento->id_foto = $request->input('id_foto');
        $depoimento->texto = $request->input('texto');
        $depoimento->tipo_franqueado = $request->input('tipo_franqueado');         
        $depoimento->status = $status;
        $depoimento->save();

        return response()->json([
            'status'=>'Atualizado com sucesso', 200,
            
        ]);
    }

// DELETE
    public function delete(Request $request,$id)   {

        $depoimento = Depoimentos::find($id);

        $depoimento->delete();

        return response()->json(['Status' => 'Deletado com sucesso', 200]);
    }

// TOGGLE SWITCH
    public function status(Request $request){

        $data = $request->all();

        Depoimentos::where('id',$data['id'])->update(['status'=> $data['status']]);

        return response()->json([

            'status' => 'Alterado com sucesso' 
        ]);

    }

// Procurar
    public function procurar(Request $request){

        $data = $request->all();
       
        $depoimentos = Depoimentos::query();
  
        if($data['procurar'] != "")
        {
            
            $depoimentos->whereRaw('LOWER(texto) LIKE ?', ['%' . $data['procurar'] . '%'])->get();
        }
  
        $depoimentos = $depoimentos->paginate(5); 
       
        return view('admin.depoimentos._list', compact('depoimentos'));
    }

}
