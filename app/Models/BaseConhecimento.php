<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseConhecimento extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [    

        'id_categoria',
        'titulo',
        'descricao',
        'tipo',
        'status',
    ];

    public function categoria(){
        return $this->hasOne(BaseConhecimentoCategorias::class,'id','id_categoria');
    }
}
