<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuracoes;
use App\Models\FormularioFranquia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class FormularioFranquiaController extends Controller
{


    public function index()
    {
        $formularios = FormularioFranquia::orderBy('created_at', 'desc')->paginate(5);

        return view('admin.formulario_franquia.index', compact('formularios'));
    }

    public function store(Request $request)
    {

        $data = $request->except('token');


        FormularioFranquia::create($data);


        $email = Configuracoes::where(['param' => 'formulario-franquia'])->first()->value;

        // Email de interesse
        $dataSender['sendAdmin'] = 'Administrador';
        $dataSender['sendDestino'] = $email;

        $dataSender['sendMail'] = strtolower($data['email']);
        $dataSender['sendName'] = ($data['nome']);
        $dataSender['sendTelefone'] = ($data['telefone']);
        $dataSender['sendCidade'] = ($data['cidade']);
        $dataSender['sendEstado'] = ($data['estado']);
        $dataSender['sendCapital'] = ($data['capital']);

        $dataSender['url']  = 'www.facilitoyfranquia.com.br';

        Mail::send('emails.formulario-franquia', $dataSender, function ($m) use ($dataSender) {
            $m->from('send@dvelopers.com.br', 'FaciliToyFranquia');
            $m->to($dataSender['sendDestino'], $dataSender['sendAdmin'])->subject('Formulario de Interesse - Franquia');
        });

        return response()->json(['status' => 'Formulario enviado com sucesso']);
    }



    public function delete($id)
    {
        FormularioFranquia::where('id', $id)->delete();

        return response()->json(['status' => 'Formulario deletado com sucesso']);
    }




    //Preview
    public function preview(Request $request, $id)
    {

        $usuario = User::find($id);

        return view('admin.usuarios.preview', compact('usuario'));
    }
}
