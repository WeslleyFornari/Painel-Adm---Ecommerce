<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Carrinhos extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'carrinhos';
    protected $fillable = [
        'session_id',
        'id_cliente',
        'id_produto',
        'id_franquia',
        'valor_unitario',
        'qtd',
        'valor_total',
        'data_entrega',
        'data_devolucao',
        'status',
    ];

    public function produto(){
        return  $this->hasOne(Produtos::class,'id','id_produto');
    }
  
}
