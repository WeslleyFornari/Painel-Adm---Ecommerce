<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cupons;
use App\Models\Franquias;
use App\Models\Grupos;
use App\Models\Produtos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CuponsController extends Controller
{

    // LISTA
    public function index(Request $request)
    {

        $data = $request->all();
        $franquia = $request->input('franquia');

        if (Auth::user()->role != 'franqueado') {
            
            $query = Cupons::query();

            if ($franquia == 'trip') {

                $query->where('tipo_franqueado', 'trip')->get();
            } elseif ($franquia == 'toy') {

                $query->where('tipo_franqueado', 'toy')->get();
            }
        } else {

            $query = Cupons::query()->where('id_franquia', Auth::user()->id_franquia);
        }

        if ($request->has('termo')) {

            $termo = $request->input('termo');
            $query->where('codigo', 'LIKE', "%$termo%");
        }

        $cupons = $query->orderBy('created_at', 'desc')->paginate(10);
        $franquias = Franquias::where('tipo_franqueado', 'toy')->get();

        if ($request->ajax()) {

            return view('admin.cupons._list-cupons', compact('cupons', 'franquias'))->render();
        }

        return view('admin.cupons.index', compact('cupons', 'franquias'));
    }



    // public function listaCupom(){

    //     $cupons = Cupons::paginate(5);
    //     return view('admin.cupons._list-cupons', compact('cupons'));
    // }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|unique:cupons,codigo',
        ], [
            'codigo.required' => 'O campo código é obrigatório.',
            'codigo.unique' => 'Este código já foi cadastrado.'
        ]);

        $data = $request->all();
        $data['valor'] = saveMoney($data['valor']);
        $data['valor_minimo'] = saveMoney($data['valor_minimo']);
        $data['codigo'] = strtoupper($data['codigo']);
        $data['tipo_franqueado'] = $request->input('tipo_franqueado');

        Cupons::create([
            'codigo' => $data['codigo'],
            'modalidade' => $data['modalidade'],
            'tipo' => $data['tipo'],
            'tipo_franqueado' => $data['tipo_franqueado'],
            'id_franquia' => $data['id_franquia'] ?? null,
            'qtd' => $data['qtd'],
            'valor' =>  $data['valor'],
            'valor_minimo' => $data['valor_minimo'],
        ]);

        $cupons = Cupons::paginate(5);

        return response()->json(['success' => true]);
    }

    // EDIT
    public function edit(Request $request, $id)
    {

        $cupom = Cupons::find($id);
        $cupom['valor'] = getMoney($cupom->valor);
        $cupom['valor_minimo'] = getMoney($cupom->valor_minimo);

        // return view('admin.cupons.cadastrar', compact('cupom'));
        return response()->json($cupom);
    }

    //UPDATE   
    public function update(Request $request)
    {

        $data = $request->except('_token'); //recebe as informações no objeto.

        $data['codigo'] = strtoupper($data['codigo']);
        $data['valor'] = saveMoney($data['valor']);
        $data['valor_minimo'] = saveMoney($data['valor_minimo']);
        $data['tipo_franqueado'] = $request->input('tipo_franqueado');
        $cupom = Cupons::find($data['id']);

        $cupom->update([
            'codigo' => $data['codigo'],
            'modalidade' => $data['modalidade'],
            'tipo' => $data['tipo'],
            'tipo_franqueado' => $data['tipo_franqueado'],
            'id_franquia' => $data['id_franquia'] ?? null,
            'qtd' => $data['qtd'],
            'valor' =>  $data['valor'],
            'valor_minimo' => $data['valor_minimo'],
        ]);

        return response()->json(['success' => true]);
    }

    // DELETAR
    public function delete(Request $request, $id)
    {

        $cupom = Cupons::find($id);
        $cupom->delete();

        $cupons = Cupons::paginate(5);

        return response()->json(['success' => true]);
    }

    // // SELECT STATUS
    // public function status(Request $request){

    //     $data = $request->all();

    //     Cupons::where('id',$data['id'])->update(['status'=> $data['status']]);

    //     return response()->json([
    //         'Status' => 'Alterado com sucesso.'
    //     ]);

    //     //return view('admin.cupons._list-cupons', compact('cupons'));
    // }

    public function mudarStatus($id = null)
    {
        $cupom = Cupons::find($id);

        if ($cupom->status == 'ativo') {
            $cupom->status = 'inativo';
        } else {
            $cupom->status = 'ativo';
        }

        $cupom->save();

        return response()->json(['status' => 'ok']);
    }
}
