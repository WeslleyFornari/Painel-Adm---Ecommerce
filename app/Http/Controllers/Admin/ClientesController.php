<?php

namespace App\Http\Controllers\Admin;

use App\Models\DadosClientes;
use App\Models\Enderecos;
use App\Models\Franquias;
use App\Models\Pedidos;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ClientesController extends Controller
{


    // LISTA
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $campo = $request->input('campo');
         
        } else {
            $campo = 'cliente';
        }

        $termo = $request->input('termo');
        $query = User::query();

        if (Auth::user()->role == 'franqueado') {

            if ($campo == 'cliente') {
              
                $pedidos = Pedidos::where('id_franquia', Auth::user()->id_franquia)->get();
                $clientesIds = $pedidos->pluck('id_cliente')->unique();
                $query->whereIn('id', $clientesIds);
            } else {
               
                $query->where('id', '!=', Auth::user()->id)->where('role', 'user');
            }
        } else {
            $query->where('id', '!=', Auth::user()->id)->where('role', 'user');
        }

        if ($request->has('campo') && $request->has('termo')) {



            if ($campo == 'cliente') {
                $query->where('name', 'LIKE', "%$termo%");
            } elseif ($campo == 'cpf') {
                $query->whereHas('dados', function ($query) use ($termo) {
                    $query->where('cpf', 'LIKE', "%$termo%");
                });
            }
        }
        $clientes = $query->orderBy('created_at', 'desc')->paginate(10);

        if ($request->ajax()) {

            return view('admin.clientes._list-clientes', compact('clientes'))->render();
        } else {

            return view('admin.clientes.index', compact('clientes'));
        }
    }

    public function new()
    {
        $selecionarFranquia = Franquias::where('status', 'ativo')->select()->distinct()->get();

        $cliente = null;

        return view('admin.clientes.new', compact('selecionarFranquia', 'cliente'));
    }

    // STORE
    public function store(Request $request)
    {

        $request->except('_token');

        $request->validate([

            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);
        $cliente = new User;
        $cliente->id_franquia = Auth::user()->id_franquia;
        $cliente->name = $request->input('name');
        $cliente->email = $request->input('email');
        $cliente->role = 'user';
        $cliente->status = 'ativo';
        $cliente->save();

        $dado_cliente = new DadosClientes;

        $dado_cliente->id_user = $cliente->id;

        $dado_cliente->save();

        return response()->json([

            'status' => 'ok',
            'id_user' => $cliente->id,
        ]);
    }

    // EDIT
    public function edit(Request $request, $id_user)
    {

        $cliente = User::find($id_user);

        return view('admin.clientes.new', compact('cliente'));
    }

    //UPDATE   
    public function update(Request $request, $id)
    {

        $cliente = User::find($id);
        $request->except('_token');

        $request->validate([

            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',

        ]);
        $cliente->name = $request->input('name');
        $cliente->email = $request->input('email');
        $cliente->role = 'user';
        $cliente->update();

        $dado_cliente = DadosClientes::where('user_id', $cliente->id);

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

        $dado_cliente->update();

        return response()->json([

            'status' => 'ok',
            'id_user' => $cliente->id,
        ]);
    }

    public function mudarStatus($id = null)
    {
        $cliente = User::find($id);

        if ($cliente->status == 'ativo') {
            $cliente->status = 'inativo';
        } else {
            $cliente->status = 'ativo';
        }

        $cliente->save();

        return response()->json(['status' => 'ok']);
    }

    // PROCURAR
    public function procurar(Request $request)
    {

        $data = $request->all();
        $clientes = User::query();

        if (Auth::user()->role == 'franqueado') {

            $pedidos = Pedidos::where('id_franquia', Auth::user()->id_franquia)->get();
            $clientesIds = $pedidos->pluck('id_cliente')->unique();
            $clientes->whereIn('id', $clientesIds)->get();
        } else {
            $clientes->where('id', '!=', Auth::user()->id)->where('role', 'user')->get();
        }

        if ($request->has('procurar') && $data['procurar'] != "") {

            $clientes->whereRaw('LOWER(name) LIKE ?', ['%' . $data['procurar'] . '%'])->get();
        }

        $clientes = $clientes->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.clientes._list-clientes', compact('clientes'));
    }

    //Preview
    public function preview(Request $request, $id)
    {

        $usuario = User::find($id);

        return view('admin.clientes.preview', compact('usuario'));
    }

    public function delete($id)
    {

        $usuario = User::find($id)->delete();

        return response()->json(['status' => 'ok',
                                 'message' => 'Cliente deletado com sucesso.']);
    }

    public function deleteEndereco($id = null)
    {

        Enderecos::find($id)->delete();

        return response()->json(['status' => 'ok']);
    }

    public function enderecos($idUsuario = null, Request $request)
    {
        $data = $request->except('_token');

        $request->validate([
            'apelido' => 'required|string|max:255',
            'cep' => 'required',
            'endereco' => 'required',
            'bairro' => 'required',
            'numero' => 'required',
            'cidade' => 'required',
            'estado' => 'required',
            'pais' => 'required'
        ]);

        $endereco = Enderecos::create([
            'id_user' => $idUsuario,
            'apelido' => $data['apelido'],
            'cep' => $data['cep'],
            'endereco' => $data['endereco'],
            'numero' => $data['numero'],
            'complemento' => $data['complemento'] ?? null,
            'bairro' => $data['bairro'],
            'cidade' => $data['cidade'],
            'estado' => $data['estado'],
            'pais' => 'BR',
            'status' => 'ativo',
        ]);

        return response()->json($endereco);
    }
}
