<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permissoes extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'permissoes';

    protected $fillable = [
        'id_user',
        'id_pagina',
        'criar',
        'editar',
        'visualizar',
        'deletar',
        // 'status'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class,'id_user');
    }
    public function pagina()
    {
        return $this->belongsTo(Paginas::class,'id_pagina');
    }
}
