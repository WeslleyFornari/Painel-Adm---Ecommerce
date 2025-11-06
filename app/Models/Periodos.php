<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Periodos extends Model
{
    use HasFactory;                                     

    protected $table = 'periodos';

    protected $fillable = [    

        'id_franquia',
        'dias',
        'valor_periodo',
    ];

}
