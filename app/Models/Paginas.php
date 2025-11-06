<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paginas extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'paginas';

    protected $fillable = [
        'titulo',
        'slug',
        'criar',
        'editar',
        'visualizar',
        'deletar',
        // 'status'
    ];

}
