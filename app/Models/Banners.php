<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banners extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [

        'titulo',
        'ordem',
        'id_media_desktop',
        'id_media_mobile',
        'tipo_franqueado',
        'url',
        'new_window',
        'tipo'

    ];

    public function midiaDinamica($collum){
        return $this->hasOne(Media::class,'id',$collum)->first();
    }

    public function imagem01(){
        return $this->hasOne(Media::class,'id','id_media_desktop');
    }
    public function imagem02(){
        return $this->hasOne(Media::class,'id','id_media_mobile');
    }

   
}
