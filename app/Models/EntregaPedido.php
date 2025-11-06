<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EntregaPedido extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'entrega_pedido';

    protected $fillable = [
        'id_pedido',
        'id_itens_pedido',
        'id_item_estoque',
        'data_entrega',
        'data_devolucao',
        'hora_entrega_de',
        'hora_entrega_ate',
        'hora_devolucao_de',
        'hora_devolucao_ate',
        'status'
    ];

    public function estoque()
    {
        return $this->belongsTo(Estoque::class,'id','id_item_estoque');
    }
    public function pedido()
    {
        return $this->belongsTo(Pedidos::class, 'id_pedido');
    }

    public function item()
{
    return $this->belongsTo(ItensPedidos::class, 'id_itens_pedido');
}

    public function entrega()
    {
        return $this->belongsTo(Enderecos::class, 'pedido()->id_endereco_entrega');
    }
}
