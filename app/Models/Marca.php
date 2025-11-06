<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Marca extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [

        'media_id',
        'nome'

    ];

    public function midiaDinamica($collum)
    {
        return $this->hasOne(Media::class, 'id', $collum)->first();
    }

    public function media()
    {
        return $this->hasOne(Media::class, 'id', 'media_id');
    }

    public function imagem(){
        return $this->hasOne(Media::class,'id','media_id');
    }
}
