<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItensPedidos extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'itens_pedido';
    protected $fillable = [
        'id_pedido',
        'id_produto',
        'id_item_estoque',
        'qtd',
        'valor_unitario',
        'valor_total',
        'id_entrega_pedido',
        'status'
    ];

    public function produto(){
        return  $this->hasOne(Produtos::class,'id','id_produto')->withTrashed();
    }
    public function pedido()
    {
        return $this->belongsTo(Pedidos::class, 'id_pedido');
    }
    
    public function entrega(){
        return  $this->hasOne(EntregaPedido::class,'id','id_entrega_pedido');
    }
    public function estoque(){
        return  $this->hasOne(Estoque::class,'id','id_item_estoque');
    }



  
}
