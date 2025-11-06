<?php

namespace App\Http\Controllers\Admin;

use App\Models\DuvidasFrequentes;
use Illuminate\Http\Request;

class DuvidasFrequentesController extends Controller
{
   
    
// LISTA
    public function index(Request $request){
            
        $duvidas = DuvidasFrequentes::paginate(5);

        return view('admin.duvidas.index', compact('duvidas'));
    }

// NEW
    public function new(){

        return view('admin.duvidas.new');
    }


// STORE
    public function store(Request $request){

        $data =  $request->except('_token');

        $status = isset($data['status']) ? $data['status'] : 'inativo'; 
        
        $duvida = new DuvidasFrequentes();

        $duvida->pergunta = $request->input('pergunta');
        $duvida->resposta = $request->input('resposta');
        // $duvida->tipo = $request->input('tipo');
        $duvida->tipo_franqueado = $request->input('tipo_franqueado');         
        $duvida->status = $status;

        $duvida->save();

        return response()->json([
            'status'=>'Salvo com sucesso',
        ]);
    }

// EDIT
    public function edit(Request $require,$id){

        $duvida = DuvidasFrequentes::find($id);
        
        return view('admin.duvidas.edit',compact('duvida'));
    }


// UPDATE
    public function update(Request $request,$id){

        $data =  $request->except('_token');

        $duvida = DuvidasFrequentes::find($id);

        $status = isset($data['status']) ? $data['status'] : 'inativo'; 
        
        $duvida->pergunta = $request->input('pergunta');
        $duvida->resposta = $request->input('resposta');
        // $duvida->tipo = $request->input('tipo');
        $duvida->tipo_franqueado = $request->input('tipo_franqueado');         
        $duvida->status = $status;

        $duvida->save();

        return response()->json([
            'status'=>'Atualizado com sucesso', 200,
            
        ]);
    }

// DELETE
    public function delete(Request $request,$id)   {

        $duvida = DuvidasFrequentes::find($id);

        $duvida->delete();

        return response()->json(['Status' => 'Deletado com sucesso', 200]);
    }

// TOGGLE SWITCH
    public function status(Request $request){

        $data = $request->all();

        DuvidasFrequentes::where('id',$data['id'])->update(['status'=> $data['status']]);

        return response()->json([

            'status' => 'Alterado com sucesso' 
        ]);

    }

// Procurar
    public function procurar(Request $request){

        $data = $request->all();
    
        $duvidas = DuvidasFrequentes::query();

        if($data['procurar'] != "")
        {
            
            $duvidas->whereRaw('LOWER(pergunta) LIKE ?', ['%' . $data['procurar'] . '%'])->get();
        }

        $duvidas = $duvidas->paginate(5); 
    
        return view('admin.duvidas._list', compact('duvidas'));
    }
}
