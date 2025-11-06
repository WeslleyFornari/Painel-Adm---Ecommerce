<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enderecos extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'enderecos';
    
    protected $fillable = [
        'id_user',
        'session_id',
        'apelido',
        'cep',
        'endereco',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'pais',
        'tipo',
        'status'
    ];

    public function enderecoCompleto(){
        $endereco =  $this->endereco . ', n°' . $this->numero;
        if($this->complemento != ""){
            $endereco .= " " . $this->complemento;
        }

        $endereco .= " - " . $this->bairro;
        return $endereco;
    }

    public function enderecoCompleto2(){
        $endereco =  'CEP: ' . $this->cep . ' - ' . $this->cidade . ', ' . $this->estado;
        return $endereco;
    }

  
}
