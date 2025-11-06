<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Avaliacoes extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [

        'id_pedido', 
        'id_produto', 
        'id_cliente',
        'id_franqueado',
        'classificacao',
        'descricao'
        
    ];

    public function cliente(){
        return $this->hasOne(User::class,'id','id_cliente');
    }
    public function pedido(){
        return $this->hasOne(Pedidos::class,'id','id_pedido');
    }
    public function produto(){
        return $this->hasOne(Produtos::class,'id','id_produto');
    }
}
