<?php

namespace App\Http\Controllers\Admin;

use App\Models\DadosClientes;
use App\Models\Franquias;
use App\Models\Paginas;
use App\Models\Permissoes;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UsuarioController extends Controller
{


    // LISTA
    public function index(Request $request)
    {

        $id_franquia = Auth::user()->id_franquia;

        $usuarios = User::where('id', '!=', Auth::user()->id)->where('role', '!=', 'user')->orderBy('created_at', 'desc')->paginate(4);

        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function new()
    {
        $selecionarFranquia = Franquias::where('status', 'ativo')->select()->distinct()->get();

        return view('admin.usuarios.new', compact('selecionarFranquia'));
    }

    // STORE
    private function getPermissoes($role, $tipoFranquia, $slug)
    {
        $permissoesCompletasTrip = ['dashboard', 'relatorios', 'calendarios', 'clientes', 'pedidos', 'estoque', 'regiao_atendida', 'cep_bloqueado'];
        $permissoesCompletasOutro = [...$permissoesCompletasTrip, 'produtos', 'cupons', 'agenda']; 
    
        if ($role === 'franqueado') {
            $permissoesValidas = ($tipoFranquia === 'trip') ? $permissoesCompletasTrip : $permissoesCompletasOutro;
            return in_array($slug, $permissoesValidas) ? ['sim', 'sim', 'sim', 'sim'] : ['nao', 'nao', 'nao', 'nao'];
        }
    
        return ['sim', 'sim', 'sim', 'sim'];
    }
    
    public function store(Request $request)
    {
        $data = $request->except('_token');
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.confirmed' => 'A confirmação da senha não corresponde.'
        ]);
    
        $usuario = new User;
        $usuario->fill([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'role' => $request->input('role'),
            'password' => bcrypt($request->input('password')),
            'status' => 'ativo',
            'id_franquia' => $request->input('id_franquia')
        ]);
        $usuario->save();
    
        $dado_cliente = new DadosClientes(['id_user' => $usuario->id]);
        $dado_cliente->save();
    
        $paginas = Paginas::all();
        foreach ($paginas as $pagina) {
            $permissao = new Permissoes(['id_user' => $usuario->id, 'id_pagina' => $pagina->id]);
            [$visualizar, $criar, $editar, $deletar] = $this->getPermissoes($usuario->role, $usuario->franquia->tipo_franqueado ?? null, $pagina->slug);
    
            $permissao->fill([
                'visualizar' => $visualizar,
                'criar' => $criar,
                'editar' => $editar,
                'deletar' => $deletar,
            ]);
    
            $permissao->save();
        }
    
        return response()->json([
            'status' => 'ok',
            'id_user' => $usuario->id,
        ]);
    }

    // EDIT
    public function edit(Request $request, $id)
    {
        
        $usuario = User::find($id);
        $selecionarFranquia = Franquias::where('status', 'ativo')->select()->distinct()->get();

        return view('admin.usuarios.new', compact('usuario', 'selecionarFranquia'));
    }

    //UPDATE   
    public function update(Request $request, $id)
    {
        $usuario = User::find($id);
        $request->except('_token');
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
        ]);
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ], [
                'password' => 'Senha não confere',
            ]);
        }

        if ($request->filled('id_franquia')) {
            $usuario->id_franquia = $request->input('id_franquia');
        }
        $usuario->name = $request->input('name');
        $usuario->email = $request->input('email');
        $usuario->role = $request->input('role');

        if ($request->filled('password')) {
            $usuario->password = bcrypt($request->input('password'));
        }

        $usuario->save();

        $dado_cliente = DadosClientes::where('id_user', $usuario->id)->first();

        if ($request->filled('cpf')) {
            $dado_cliente->cpf = $request->input('cpf');
        }
        if ($request->filled('cnpj')) {
            $dado_cliente->cnpj = $request->input('cnpj');
        }
        if ($request->filled('celular')) {
            $dado_cliente->celular = $request->input('celular');
        }
        if ($request->filled('data_nascimento')) {
            $dado_cliente->data_nascimento = $request->input('data_nascimento');
        }
        if ($request->filled('telefone')) {
            $dado_cliente->telefone = $request->input('telefone');
        }

        $dado_cliente->save();

        $permissoes = Permissoes::where('id_user', $usuario->id)->get();

        if ($permissoes->count() > 0){
            
            Permissoes::where('id_user', $usuario->id)->delete();

            $paginas = Paginas::all();
    
            foreach ($paginas as $pagina) {
                $permissao = new Permissoes(['id_user' => $usuario->id, 'id_pagina' => $pagina->id]);
                [$visualizar, $criar, $editar, $deletar] = $this->getPermissoes($usuario->role, $usuario->franquia->tipo_franqueado ?? null, $pagina->slug);
        
                $permissao->fill([
                    'visualizar' => $visualizar,
                    'criar' => $criar,
                    'editar' => $editar,
                    'deletar' => $deletar,
                ]);
        
                $permissao->save();
            }
        }

        return response()->json([
            'status' => 'editado',
            'id_user' => $usuario->id,
        ]);
    }


    // TOGGLE SWITCH
    // public function status(Request $request){

    //     $data = $request->all();

    //     User::where('id',$data['id'])->update(['status'=> $data['status']]);

    //     if(Auth::User()->role == "master"){

    //         $usuarios = User::all();
    //     }

    //     if(Auth::User()->role == "admin"){

    //         $id_franquia = Auth::User()->id_franquia;
    //         $usuarios = User::where('id_empresa', $id_franquia)->get();
    //     }

    //     return view('admin.usuarios._list-usuarios', compact('usuarios'));
    // }

    public function mudarStatus($id = null)
    {
        $usuario = User::find($id);

        if ($usuario->status == 'ativo') {
            $usuario->status = 'inativo';
        } else {
            $usuario->status = 'ativo';
        }

        $usuario->save();

        return response()->json(['status' => 'ok']);
    }

    // PROCURAR
    public function procurar(Request $request)
    {

        $data = $request->all();

        $usuarios = USER::query();

        if ($request->has('procurar') && $data['procurar'] != "") {

            $usuarios->whereRaw('LOWER(name) LIKE ?', ['%' . $data['procurar'] . '%'])->get();
        }

        $usuarios = $usuarios->paginate(2);

        return view('admin.usuarios._list-usuarios', compact('usuarios'));
    }

    //Preview
    public function preview(Request $request, $id)
    {

        $usuario = User::find($id);

        return view('admin.usuarios.preview', compact('usuario'));
    }
    public function delete($id)
    {

        $usuario = User::find($id)->delete();

        return response()->json(['status' => 'ok']);
    }
}
