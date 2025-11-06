<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Localizacao extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'localizacao';

    protected $fillable = [
        'session_id',
        'franquia_id',
        'cep',
        'bairro',
        'estado',
        'data_inicio',
        'data_fim'
    ];


    public function franquia(){
        return  $this->hasOne(Franquias::class,'id','franquia_id');
    }
}
