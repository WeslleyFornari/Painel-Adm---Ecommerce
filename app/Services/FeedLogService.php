<?php

namespace App\Services;

use App\Models\FeedLog;
use Illuminate\Support\Facades\Log;

class FeedLogService
{
    public function saveLog($qtdProdutos, $horaInicio, $horaFim, $sucesso)
    {
        $logData = [
            'qtd_produtos' => $qtdProdutos,
            'hora_inicio'  => $horaInicio,
            'hora_fim'     => $horaFim,
            'sucesso'      => $sucesso,
        ];

        $feedLog = FeedLog::create($logData);

        Log::info('Feed Log salvo', $logData);

        return $feedLog;
    }
}
