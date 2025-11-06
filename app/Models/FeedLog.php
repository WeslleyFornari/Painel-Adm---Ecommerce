<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedLog extends Model
{
    use HasFactory;

    protected $table = 'feed_logs';

    protected $fillable = [
        'qtd_produtos',
        'hora_inicio',
        'hora_fim',
        'sucesso',
    ];
}
