<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DadosClientes extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'dados_clientes';

    protected $fillable = [

        'id_user',
        'cpf',
        'cnpj',
        'data_nascimento',
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
        'status',
        'id_gateway'
         
    ];


}
