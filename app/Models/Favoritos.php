<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Favoritos extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'favoritos';
    
    protected $fillable =  [

            'id_user',
            'id_produto'
    ];

    public function produto(){
        return $this->hasOne(Produtos::class,'id','id_produto');
    }
}
