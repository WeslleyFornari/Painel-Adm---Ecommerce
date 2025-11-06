<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estoque extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'estoques';

    protected $fillable = [

        'id_produto', 
        'id_franqueado', 
        'qtd',
        'codigo',
        'tipo_locacao',
        'data_compra',
        'valor_compra',
        'status',
        
    ];

    public function franquia()
    {
        return $this->hasOne(Franquias::class, 'id', 'id_franqueado');
    }

    public function produto()
    {
        return $this->belongsTo(Produtos::class, 'id_produto');
    }

    public function alugueis()
    {
        return $this->hasMany(EntregaPedido::class, 'id_item_estoque', 'id');
    }
    public function uso()
    {
        return $this->hasMany(EntregaPedido::class, 'id_item_estoque', 'id')
        ->whereHas('pedido', function ($query) {
            $query->where('id_status', '>=', 3);
            $query->where('id_status', '!=', 10);
        })
        ->whereHas('item', function ($query) {
            $query->where('tipo_locacao', '!=', 'venda');
        });
    }

    public function disponibilidade($data_inicio = null, $data_final = null)
    {
        if ($data_inicio !== null && $data_final !== null) {
            return EntregaPedido::where(
                'data_devolucao',
                '>',
                $data_inicio
            )->where(
                'data_entrega',
                '<',
                $data_final
            )->count();
        }
    }

    public function getOptions()
    {
        return Estoque::all()->map(function ($estoque) {
            return [
                'id' => $estoque->id,
                'label' => $estoque->codigo,
                'value' => $estoque->id,
                'type' => $estoque->id_produto,
                'status' => $estoque->status
            ];
        })->toArray();
    }
    
}
