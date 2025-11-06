<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PeriodosValores extends Model
{
    use HasFactory;                                     

    protected $table = 'periodos_valores';

    protected $fillable = [    

        'id_periodo',
        'id_produto',
        'valor_periodo',
    ];

    public function periodo()
    {
        return $this->hasOne(Periodos::class, 'id', 'id_periodo');
    }



}
