<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Franquias extends Model
{
    use HasFactory, SoftDeletes;                                     

    protected $table = 'franquias';

    protected $fillable = [    

        'nome_responsavel',
        'nome_franquia',
        'prefix',
        'cpf',
        'cnpj',
        'celular',
        'telefone',
        'cep',
        'endereco',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'pais',
        'email',
        'tipo_franqueado',
        'retirada_balcao',
        'frete_economico',
        'frete_expresso',
        'status',
        'instagram',
        'facebook',
        'youtube',
        'percentual_automatico_franqueado',
        'percentual_manual_franqueado',
        'cod_franqueado',
        'subdominio',
        'apiKey',
        'gateway',
        'apelido',
        'chave_secreta_inter',
        'chave_publica_inter',
        'chave_pix_inter',
        'certificado_inter', //arquivo .crt
        'chave_inter', //arquivo.key
        'webhook_inter' //arquivo .ca
    ];

    public function midiaDinamica($collum){
        return $this->hasOne(Media::class,'id',$collum)->first();
    }

    public function periodos(){
        return $this->hasMany(Periodos::class, 'id_franquia');
    }
    
    public function checkIntegracao($name){
       
        $check = Integracoes::where('nome',$name)->first();
        if(!$check){
            $check =  Integracoes::create([
                'id_empresa'=>$this->id,
                'nome'=>$name,
                'status'=>'inativo',
            ]);
        }
        return $check;
    }

}
