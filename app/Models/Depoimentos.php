<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Depoimentos extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [

        // 'id_foto',
        'texto',
        'tipo_franqueado',
        'status'
    ];


    public function midiaDinamica($collum){
        return $this->hasOne(Media::class,'id',$collum)->first();
    }

  public function imagem(){
        return $this->hasOne(Media::class,'id','id_foto');
    }

}
