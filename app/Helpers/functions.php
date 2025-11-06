<?php

use App\Models\Configuracoes;
use App\Models\Igreja;
use App\Models\Localizacao;
use App\Models\Carrinhos;
use App\Models\Paginas;
use App\Models\PeriodosValores;
use App\Models\Periodos;
use App\Models\Agenda;
use App\Models\Franquias;
use App\Models\Permissoes;
use App\Models\RegiaoAtendida;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

function empresa()
{
    return Auth::user()->empresa;
}

function logoAdmin(){
    
    switch (Auth::user()->role) {
        case 'master':
            return  asset('assets/img/logos/trip-toy.png');
            break;
        case 'admin':
            return asset('assets/img/logos/trip-toy.png');
            break;
        case 'franqueado':
            return asset('assets/img/logos/'.Auth::user()->franquia->tipo_franqueado.'.png');
            break;
    }

}
function getMoney($value, $moeda = null)
{
    if ($value === null) {
        return '0,00';
    }
    if ($moeda !== null) {
        return $moeda . " " . number_format($value, 2, ',', '.');
    } else {
        return @number_format($value, 2, ',', '.');
    }
}
if (!function_exists('saveMoney')) {
    function saveMoney($value)
    {

        if ($value === null) {
            return 0.00;
        }
        $money = str_replace(".", "", $value);
        $money = str_replace(",", ".", $money);
        return $money;
    }
}

function localizacao(){
    $sessionId = Session::getId();

    $localizacao = Localizacao::where([
         'session_id'=> $sessionId,
     ])->first();
   return  $localizacao;
}

function carrinho_localizacao(){
    $sessionId = Session::getId();

    $carrinhos = Carrinhos::where('session_id', $sessionId)->get();

    if ($carrinhos){
        return $carrinhos->count();
    }
    else{
        return null;
    }
}

function permissoes(){
    $permissoes = Permissoes::where('id_user', Auth::user()->id)->where('visualizar', 'sim')->get();
    return $permissoes;
}
function permissao($slug){
    $pagina = Paginas::where('slug', $slug)->first();
    $permissao = Permissoes::where('id_user', Auth::user()->id)->where('id_pagina', $pagina->id)->first();
    return $permissao;
}
function periodo_min($id_produto) {
    $periodo_min = PeriodosValores::where('id_produto', $id_produto)
    ->join('periodos', 'periodos_valores.id_periodo', '=', 'periodos.id')
    ->orderBy('periodos.dias', 'asc')->first();
    return $periodo_min;
}
function periodos_sem_franquia() {
    $periodos = Periodos::where('id_franquia', null)->get();
    return $periodos;
}


function FirstName($name){
    $nome = explode(' ', trim($name));
    return $nome[0];
}
function tipo_franquia($franquia){
    $franquia = Franquias::find($franquia);
    
    return $franquia->tipo_franqueado;
}


if (!function_exists('agenda_dias_bloqueados')) {
    function agenda_dias_bloqueados($franquia)
    {
        $agenda = Agenda::where('id_franquia', $franquia->id)->first();
        $intervalos = explode(', ', $agenda->dias_bloqueados ?? ''); 
        if($agenda && $agenda->dias_bloqueados){
            return array_map(function ($intervalo) {
                [$from, $to] = explode(' ate ', $intervalo);
                return [
                    'data_inicio' => trim($from),
                    'data_fim'   => trim($to)
                ];
            }, $intervalos);
        }
        else{
            return null;
        }
    }
}
if (!function_exists('agenda_dia_semana')) {
    function agenda_dia_semana($franquia, $dia)
    {
        $agenda = Agenda::where('id_franquia', $franquia->id)->first();
        $dias_da_semana = explode(', ', $agenda->dias_semanas ?? ''); 

        foreach($dias_da_semana as $dia_semana){
           if($dia_semana == $dia){
             return true;
           }
        }
        
    }
}
if (!function_exists('itens_pedidos')) {
    function itens_pedidos($pedido)
    {
        $dia_entrega = null;
        $dia_devolucao = null;
        $data = [];

        $itens = $pedido->itens()->get();

        foreach ($itens as $item) {
            if ($item->entrega->data_entrega != $dia_entrega || $item->entrega->data_devolucao != $dia_devolucao) {
                $data[] = [
                    'data_entrega' => $item->entrega->data_entrega,
                    'data_devolucao' => $item->entrega->data_devolucao,
                    'hora_entrega_de' => $item->entrega->hora_entrega_de,
                    'hora_entrega_ate' => $item->entrega->hora_entrega_ate,
                    'status' => $item->status
                ];
                $dia_entrega = $item->entrega->data_entrega;
                $dia_devolucao = $item->entrega->data_devolucao;
            }
        }

        return $data;
    }
}
