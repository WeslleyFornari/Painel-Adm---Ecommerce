<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DadosClientes;
use Illuminate\Http\Request;

class DadosClientesController extends Controller
{
  
    //UPDATE   
    public function update(Request $request,$id)   {

        $request->except('_token');

        if ($request){
    
    
            $dado_cliente = DadosClientes::find($id);
    
    
            // $dado_cliente->cpf = $request->input('cpf');
          
            // $dado_cliente->cnpj = $request->input('cnpj');
            // $dado_cliente->celular = $request->input('celular');
            // $dado_cliente->telefone = $request->input('telefone');
          
            // $dado_cliente->cep = $request->input('cep');
            // $dado_cliente->endereco = $request->input('endereco');
            // $dado_cliente->numero = $request->input('numero');
            // $dado_cliente->complemento = $request->input('complemento');
            // $dado_cliente->bairro = $request->input('bairro');
            // $dado_cliente->cidade = $request->input('cidade');
            // $dado_cliente->estado = $request->input('estado');
            // $dado_cliente->pais = $request->input('pais');
            
     
            // $dado_cliente->save();
             $dado_cliente->cpf = $request->input('cpf');
             $dado_cliente->cnpj = $request->input('cnpj');
           
             $dado_cliente->celular = $request->input('celular');
    
            if ($request->filled('telefone')) {
                $dado_cliente->telefone = $request->input('telefone');
            }
            if ($request->filled('data_nascimento')) {
                $dado_cliente->data_nascimento = $request->input('data_nascimento');
            }
    
             
             $dado_cliente->update();
        }
    }
}
