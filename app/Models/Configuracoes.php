<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Configuracoes extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [

        'id_franqueado',
        'param', 
        'value', 
        'tipo_franqueado',
        'titulo'
        
    ];

    public function franquia(){
        return $this->hasOne(Franquias::class,'id','id_franquia');
    }
}
