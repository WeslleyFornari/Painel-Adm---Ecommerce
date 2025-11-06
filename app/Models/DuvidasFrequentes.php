<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DuvidasFrequentes extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'tipo',
        'pergunta',
        'resposta',
        'tipo_franqueado',
        'status'
    ];


}
