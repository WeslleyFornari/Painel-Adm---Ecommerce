<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    public function index()
    {
        $marcas = Marca::paginate(10);
        return view('admin.marcas.index', compact('marcas'));
    }

    public function store(Request $request)
    {
        $data = $request->except('_token');

        $request->validate([
            'nome' => 'required',
            'media_id' => 'required',
        ]);

        $marcas = Marca::create(
            [
                'nome' => $data['nome'],
                'status' => 1,
                'media_id' => $data['media_id']
            ]
        );

        $marcas->refresh();

        return response()->json([
            'success' => true,
            'marcas' => $marcas
        ]);
    }

    public function edit(Request $request, $id)
    {
        $marca = Marca::find($id);
        return view('admin.marcas._editar', compact('marca'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->except('_token');

        $request->validate([
            'nome' => 'required',
            'media_id' => 'required',
        ]);

        Marca::where('id', $id)->update([

            'nome' => $data['nome'],
            'media_id' => $data['media_id']
        ]);

        return response()->json(['status' => 'Atualizado ok'], 200);
    }

    public function delete($id)
    {
        Marca::where('id', $id)->delete();
        return response()->json(['message' => 'MArca deletada']);
    }
}
