<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PerguntasFrequentes extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [    

        'id_produto',
        'id_cliente',
        'email',
        'nome',
        'pergunta',
        'resposta',
        'status',
    ];

    public function produto(){
        return $this->hasOne(Produtos::class,'id','id_produto');
    }
}
