<?php

namespace App\Http\Controllers\Admin;

use App\Models\Paginas;
use App\Models\Permissoes;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PermissoesController extends Controller
{
    public function index(Request $request){

        $usuarios = User::paginate(5);
    
        return view('admin.permissoes.index', compact('usuarios'));
    }
    public function permissoes($id){

        $usuario = User::find($id);

        $permissoes = Permissoes::where('id_user', $id)->get();

        $paginas = Paginas::all();
    
        return view('admin.permissoes.permissoes', compact('permissoes', 'usuario', 'paginas'));
    }
    public function store(Request $request, $id)
    {
        $permissoes = $request->input('permissoes');
        foreach ($permissoes['id'] as $index => $id_permissao) {
            $permissao = Permissoes::find($id_permissao);
            $permissao->update([
                'visualizar' => $permissoes['visualizar'][$id_permissao] ?? 'nao',
                'criar' => $permissoes['criar'][$id_permissao] ?? 'nao',
                'editar' => $permissoes['editar'][$id_permissao] ?? 'nao',
                'deletar' => $permissoes['deletar'][$id_permissao] ?? 'nao',
            ]);
        }
        return response()->json(['status' => 'ok'], 200);
    }

    
}
