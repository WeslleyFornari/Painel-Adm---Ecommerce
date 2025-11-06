<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promocoes extends Model
{
    // use HasFactory, SoftDeletes;

    protected $table = 'promocoes';

    protected $fillable = [

        'id_produto',
        'de',
        'ate',
        'tipo',
        'desconto',
        
    ];
}