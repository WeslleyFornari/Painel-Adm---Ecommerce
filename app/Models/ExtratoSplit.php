<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtratoSplit extends Model
{
    use HasFactory;

    protected $table = 'extrato_splits';

    protected $fillable = [
        'id_ordem',
        'id_franquia',
        'valor_split',
        'percentual_split',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedidos::class, 'id_ordem');
    }

    public function franquia()
    {
        return $this->belongsTo(Franquias::class, 'id_franquia');
    }
}
