<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseStatusPedidos extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'base_status_pedidos';
    protected $fillable = [
        'nome',
        'decricao',
        'status'
    ];
  
}
