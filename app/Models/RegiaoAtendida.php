<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegiaoAtendida extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id_franqueado', 
        'bairro', 
        'cidade',
        'estado',
        'valor_entrega_expresso',
        'valor_entrega_economico',
        'tempo_entrega',
        'tipo',
        'status',
        
    ];

    public function franquia(){
        return $this->hasOne(Franquias::class,'id','id_franqueado');
    }

}

