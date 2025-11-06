<?php

namespace App\Http\Controllers;

use App\Models\BaseConhecimento;
use App\Models\BaseConhecimentoCategorias;
use App\Models\PerguntasFrequentes;
use App\Models\ProdutoCategoria;
use App\Models\Produtos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class PerguntasFrequentesController extends Controller
{
    public function index(Request $request)
    {
        $perguntas = PerguntasFrequentes::orderBy('created_at', 'desc')->get();
        $produtoIds = $perguntas->pluck('id_produto')->unique();
        $dadosPaginados = Produtos::whereIn('id', $produtoIds)->paginate(5);
        return view('admin.perguntas_frequentes.index', ['produtos' => $dadosPaginados]);
    }



    public function store($id, Request $request)
    {

        $data =  $request->except('_token');
        $pergunta_frequente = PerguntasFrequentes::create([
            'id_produto' => $id,
            'id_cliente' => Auth::user()->id ?? null,
            'email' => Auth::user()->email ?? $data['email_pergunta'],
            'nome' => Auth::user()->name ?? $data['nome_pergunta'],
            'pergunta' => $data['pergunta'],
            'resposta' => NULL,
            'status' => 'ativo',
        ]);

        return response()->json(['status' => 'ok'], 200);
    }
    public function responder($id)
    {
        $pergunta_frequente = PerguntasFrequentes::find($id);

        return response()->json($pergunta_frequente);
    }

    public function update(Request $request)
    {

        $data = $request->except('_token');

        $pergunta = PerguntasFrequentes::where('id', $data['id'])->first();

        $pergunta->update([
            'resposta' => $data['resposta'],
        ]);

        Mail::send('emails.resposta_pergunta', ['pergunta' => $pergunta], function ($m) use ($pergunta) {
            $m->from('send@dvelopers.com.br', 'Facilitrip')
                ->to($pergunta->email, $pergunta->nome)
                ->subject('Resposta da Pergunta Produto Facilitrip');
        });

        return response()->json('resposta');
    }

    public function delete(Request $request, $id)
    {

        $pergunta_frequente = PerguntasFrequentes::find($id);
        $pergunta_frequente->delete();

        return response()->json(['status' => 'ok']);
    }

    public function mudarStatus($id = null)
    {
        $pergunta_frequente = PerguntasFrequentes::find($id);

        if ($pergunta_frequente->status == 'ativo') {
            $pergunta_frequente->status = 'inativo';
        } else {
            $pergunta_frequente->status = 'ativo';
        }

        $pergunta_frequente->save();

        return response()->json(['status' => 'ok']);
    }
}
