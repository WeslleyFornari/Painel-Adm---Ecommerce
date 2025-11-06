<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProdutoCategoria extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nome',
        'id_categoria',
        'descricao',
        'slug',
        'tipo',
        'id_parent',
        'id_media',
        'status',
        'slug'
        
    ];

    public function imagem(){
        return $this->hasOne(Media::class,'id','id_media');
    }

    public function midiaDinamica($collum){
        return $this->hasOne(Media::class,'id',$collum)->first();
    }

    public function subcategoria(){
        return $this->hasOne(ProdutoCategoria::class,'id','id_parent');
    }

    public function produtos(){
        return $this->hasMany(Produtos::class, 'id_categoria');
    }
}
