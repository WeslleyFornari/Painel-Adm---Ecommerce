<?php

namespace App\Http\Controllers\Admin;


use App\Models\Periodos;
use Illuminate\Http\Request;
use App\Models\Franquias;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Storage;


class FranquiasController extends Controller
{

    public function index()
    {

        $franquias = Franquias::orderBy('created_at', 'desc')->paginate(5);


        return view('admin.franquias.index', compact('franquias'));
    }

    public function new()
    {

        return view('admin.franquias.new');
    }

    public function store(Request $request)
    {

        $data =  $request->except('_token');

        $request->validate([

            'nome_responsavel' => 'required|string|max:255',
            'nome_franquia' => 'required|string|max:255',
            'cnpj' => 'required|string|min:18',
            'cpf' => 'required|min:14',
            'celular' => 'required|min:14',
            'cep' => 'required',
            'endereco' => 'required',
            'numero' => 'required',
            'cidade' => 'required',
            'estado' => 'required',
            'pais' => 'required',

        ]);
        //  dd($data);
        $franquia = new Franquias;

        $franquia->status = $request->input('status');
        $franquia->tipo_franqueado = $request->input('tipo_franqueado');
        $franquia->nome_franquia = $request->input('nome_franquia');
        $franquia->nome_responsavel = $request->input('nome_responsavel');
        $franquia->cnpj = $request->input('cnpj');
        $franquia->cpf = $request->input('cpf');
        $franquia->celular = $request->input('celular');
        $franquia->telefone = $request->input('telefone');
        $franquia->prefix = $request->input('prefix');

        $franquia->cep = $request->input('cep');
        $franquia->endereco = $request->input('endereco');
        $franquia->numero = $request->input('numero');
        $franquia->bairro = $request->input('bairro');
        $franquia->cidade = $request->input('cidade');
        $franquia->estado = $request->input('estado');
        $franquia->pais = $request->input('pais');
        $franquia->email = $request->input('email');

        $franquia->retirada_balcao = $request->input('retirada_balcao');
        $franquia->frete_expresso = $request->input('frete_expresso');
        $franquia->frete_economico = $request->input('frete_economico');
        $franquia->subdominio = $request->input('subdominio');
        $franquia->gateway = $request->input('gateway');
        $franquia->apelido = $request->input('apelido');
        $franquia->status = 'ativo';


        if ($request->filled('complemento')) {
            $franquia->complemento = $request->input('complemento');
        }

        // if ($request->filled('cod_franqueado')) {
        //     $franquia->cod_franqueado = $request->input('cod_franqueado');
        // }
        // if ($request->filled('percentual_automatico_franqueado')) {
        //     $franquia->percentual_automatico_franqueado = $request->input('percentual_automatico_franqueado');
        // }
        // if ($request->filled('percentual_manual_franqueado')) {
        //     $franquia->percentual_manual_franqueado = $request->input('percentual_manual_franqueado');
        // }
        if ($request->filled('apiKey')) {
            $franquia->apiKey = $request->input('apiKey');
        }
        if ($request->filled('instagram')) {
            $instagram = str_replace('@', '',  $request->input('instagram'));
            $franquia->instagram = $instagram;
        }
        if ($request->filled('facebook')) {
            $facebook = str_replace('@', '',  $request->input('facebook'));
            $franquia->facebook = $facebook;
        }
        if ($request->filled('youtube')) {
            $youtube = str_replace('@', '',  $request->input('youtube'));
            $franquia->youtube = $youtube;
        }

        $franquia->save();


        // Processar arquivos do Banco Inter
        if ($request->hasFile('certificado_inter')) {
            $file = $request->file('certificado_inter');
            $fileName = 'inter_cert_' . $franquia->id . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('inter_certificates', $fileName, 'private');
            $franquia->certificado_inter = $path;
        }

        if ($request->hasFile('chave_inter')) {
            $file = $request->file('chave_inter');
            $fileName = 'inter_key_' . $franquia->id . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('inter_certificates', $fileName, 'private');
            $franquia->chave_inter = $path;
        }

        if ($request->hasFile('webhook_inter')) {
            $file = $request->file('webhook_inter');
            $fileName = 'inter_ca_' . $franquia->id . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('inter_certificates', $fileName, 'private');
            $franquia->webhook_inter = $path;
        }

        // Salvar chaves do Banco Inter
        if ($request->filled('chave_publica_inter')) {
            $franquia->chave_publica_inter = $request->input('chave_publica_inter');
        }

        if ($request->filled('chave_secreta_inter')) {
            $franquia->chave_secreta_inter = $request->input('chave_secreta_inter');
        }

        if ($request->filled('chave_pix_inter')) {
            $franquia->chave_pix_inter = $request->input('chave_pix_inter');
        }

        // Salvar novamente para garantir que os caminhos dos arquivos sejam armazenados
        $franquia->save();


        $periodos = $request->input('periodos');

        if ($periodos) {
            foreach ($periodos['dias'] as $index => $dias) {
                $periodo = new Periodos();
                $periodo->id_franquia = $franquia->id;
                $periodo->dias = $dias;
                $periodo->save();
            }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }


    public function edit(Request $require, $id)
    {

        $franquia = Franquias::find($id);

        return view('admin.franquias.edit', compact('franquia'));
    }

    public function update(Request $request, $id)
    {
        $franquia = Franquias::find($id);
        $request->except('_token');

        $request->validate([

            'nome_responsavel' => 'required|string|max:255',
            'nome_franquia' => 'required|string|max:255',
            'cnpj' => 'required|string|min:18',
            'cpf' => 'required|min:14',
            'celular' => 'required|min:13',

            'cep' => 'required',
            'endereco' => 'required',
            'numero' => 'required',
            'cidade' => 'required',
            'estado' => 'required',
            'pais' => 'required',
        ]);

        $franquia->tipo_franqueado = $request->input('tipo_franqueado');
        $franquia->nome_franquia = $request->input('nome_franquia');
        $franquia->nome_responsavel = $request->input('nome_responsavel');
        $franquia->celular = $request->input('celular');
        $franquia->telefone = $request->input('telefone');
        $franquia->prefix = $request->input('prefix');

        $franquia->cep = $request->input('cep');
        $franquia->endereco = $request->input('endereco');
        $franquia->numero = $request->input('numero');
        $franquia->complemento = $request->input('complemento');
        $franquia->bairro = $request->input('bairro');
        $franquia->cidade = $request->input('cidade');
        $franquia->estado = $request->input('estado');
        $franquia->pais = $request->input('pais');
        $franquia->email = $request->input('email');
        $franquia->retirada_balcao = $request->input('retirada_balcao');
        $franquia->frete_expresso = $request->input('frete_expresso');
        $franquia->frete_economico = $request->input('frete_economico');
        $franquia->gateway = $request->input('gateway');
        $franquia->apelido = $request->input('apelido');

        if (Auth::user()->role != 'franqueado') {
            $franquia->subdominio = $request->input('subdominio');
        }


        if ($request->filled('complemento')) {
            $franquia->complemento = $request->input('complemento');
        }

        // if ($request->filled('cod_franqueado')) {
        //     $franquia->cod_franqueado = $request->input('cod_franqueado');
        // }
        // if ($request->filled('percentual_automatico_franqueado')) {
        //     $franquia->percentual_automatico_franqueado = $request->input('percentual_automatico_franqueado');
        // }
        // if ($request->filled('percentual_manual_franqueado')) {
        //     $franquia->percentual_manual_franqueado = $request->input('percentual_manual_franqueado');
        // }

        if ($request->filled('instagram')) {
            $instagram = str_replace('@', '',  $request->input('instagram'));
            $franquia->instagram = $instagram;
        }
        if ($request->filled('facebook')) {
            $facebook = str_replace('@', '',  $request->input('facebook'));
            $franquia->facebook = $facebook;
        }
        if ($request->filled('youtube')) {
            $youtube = str_replace('@', '',  $request->input('youtube'));
            $franquia->youtube = $youtube;
        }

        if ($request->filled('apiKey')) {
            $franquia->apiKey = $request->input('apiKey');
        }

        if ($franquia->cnpj != $request->input('cnpj')) {
            $request->validate([
                'cnpj' => 'required|string|unique:franquias',
            ]);
            $franquia->cnpj = $request->input('cnpj');
        }
        if ($franquia->cpf != $request->input('cpf')) {
            $request->validate([
                'cpf' => 'required|string|unique:franquias',
            ]);
            $franquia->cpf = $request->input('cpf');
        }

        $franquia->save();


        // Processar arquivos do Banco Inter
        if ($request->hasFile('certificado_inter')) {
            // Remover arquivo antigo se existir
            if ($franquia->certificado_inter && Storage::disk('private')->exists($franquia->certificado_inter)) {
                Storage::disk('private')->delete($franquia->certificado_inter);
            }

            $file = $request->file('certificado_inter');
            $fileName = 'inter_cert_' . $franquia->id . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('inter_certificates', $fileName, 'private');
            $franquia->certificado_inter = $path;
        }

        if ($request->hasFile('chave_inter')) {
            // Remover arquivo antigo se existir
            if ($franquia->chave_inter && Storage::disk('private')->exists($franquia->chave_inter)) {
                Storage::disk('private')->delete($franquia->chave_inter);
            }

            $file = $request->file('chave_inter');
            $fileName = 'inter_key_' . $franquia->id . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('inter_certificates', $fileName, 'private');
            $franquia->chave_inter = $path;
        }

        if ($request->hasFile('webhook_inter')) {
            // Remover arquivo antigo se existir
            if ($franquia->webhook_inter && Storage::disk('private')->exists($franquia->webhook_inter)) {
                Storage::disk('private')->delete($franquia->webhook_inter);
            }

            $file = $request->file('webhook_inter');
            $fileName = 'inter_ca_' . $franquia->id . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('inter_certificates', $fileName, 'private');
            $franquia->webhook_inter = $path;
        }

        // Salvar chaves do Banco Inter
        if ($request->filled('chave_publica_inter')) {
            $franquia->chave_publica_inter = $request->input('chave_publica_inter');
        }

        if ($request->filled('chave_secreta_inter')) {
            $franquia->chave_secreta_inter = $request->input('chave_secreta_inter');
        }

        if ($request->filled('chave_pix_inter')) {
            $franquia->chave_pix_inter = $request->input('chave_pix_inter');
        }

        $franquia->save();
        
        //obter arquivos
        //$certPath = Storage::disk('private')->path($franquia->certificado_inter);
        //$keyPath = Storage::disk('private')->path($franquia->chave_inter);
        //$caPath = Storage::disk('private')->path($franquia->webhook_inter);


        $franquia->periodos()->delete();

        $periodos = $request->input('periodos');

        if ($periodos) {
            foreach ($periodos['dias'] as $index => $dias) {
                $periodo = new Periodos();
                $periodo->id_franquia = $franquia->id;
                $periodo->dias = $dias;
                $periodo->save();
            }
        }

        return response()->json([
            'status' => 'ok',

        ]);
    }

    public function delete(Request $request, $id)
    {

        $franquia = Franquias::find($id);
        if (Auth::user()->role == 'admin') {
            $franquia = Franquias::find(Auth::user()->id_franquia);
        }
        $franquia->delete();

        return response(200);
    }


    //TOGGLE SWITCH
    // public function status(Request $request){

    //     $data = $request->all();

    //     Franquias::where('id',$data['id'])->update(['status'=> $data['status']]);

    //     if(Auth::User()->role == "master"){

    //         $usuarios = User::all();
    //     }

    //     if(Auth::User()->role == "admin"){

    //         $id_franquia = Auth::User()->id_franquia;
    //         $usuarios = User::where('id_empresa', $id_franquia)->get();
    //     }

    //     return response()->json(['Status' => 'Alterado status']);
    // }

    public function mudarStatus($id = null)
    {
        $franquia = Franquias::find($id);

        if ($franquia->status == 'ativo') {
            $franquia->status = 'inativo';
        } else {
            $franquia->status = 'ativo';
        }

        $franquia->save();

        return response()->json(['status' => 'ok']);
    }

    //Preview
    public function preview(Request $request, $id)
    {

        $franquia = Franquias::find($id);

        return view('admin.franquias.preview', compact('franquia'));
    }
}
