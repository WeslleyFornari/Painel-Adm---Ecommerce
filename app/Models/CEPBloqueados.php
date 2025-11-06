<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CEPBloqueados extends Model
{
    use HasFactory;

    protected $table = 'cep_bloqueados';

    protected $fillable = [
        'id_franquia', 
        'cep', 
        'endereco', 
        'bairro', 
        'cidade',
        'estado',
        'pais',
        'status',
    ];

    public function franquia(){
        return $this->hasOne(Franquias::class,'id','id_franquia');
    }

}

