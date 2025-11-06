<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pedidos extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pedidos';
    protected $fillable = [
        'token',
        'numero',
        'id_franquia',
        'id_cliente',
        'id_endereco_entrega',
        'id_endereco_devolucao',
        'id_forma_pagamento',
        'pagamento',
        'gateway_pagamento',
        'id_transacao',
        'valor_taxa',
        'valor_frete',
        'tipo_frete',
        'tipo',
        'id_retirada',
        'valor_total',
        'valor_total_produtos',
        'valor_desconto',
        'valor_liquido',
        'id_cupom',
        'observacoes',
        'nome_receber',
        'telefone_receber',
        'id_status',
        'qr_code',
        'qr_code_url',
        'created_at',
        'observacoes_internas',
        'observacoes_cliente',
        'pagamento_parcial_inicial',
        'pagamento_parcial_final',
    ];

    public function itens(){
        return  $this->hasMany(ItensPedidos::class, 'id_pedido','id');
    }
    public function franquia(){
        return  $this->hasOne(Franquias::class,'id','id_franquia');
    }
    public function retirada(){
        return  $this->hasOne(Franquias::class,'id','id_retirada');
    }
    public function cliente(){
        return  $this->hasOne(User::class,'id','id_cliente');
    }
    public function cupom(){
        return  $this->hasOne(Cupons::class,'id','id_cupom');
    }
    public function endereco_entrega(){
        return  $this->hasOne(Enderecos::class,'id','id_endereco_entrega');
    }
    public function endereco_devolucao(){
        return  $this->hasOne(Enderecos::class,'id','id_endereco_devolucao');
    }
    public function forma_pagamento(){
        return  $this->hasOne(FormaPagamento::class,'id','id_forma_pagamento');
    }
    public function status(){
        return  $this->hasOne(BaseStatusPedidos::class,'id','id_status');
    }
    public function frete()
    {
        if ($this->tipo_frete === 'retirar_loja'){
            $frete = 'Retirada na Loja';
        }
        else if ($this->tipo_frete === 'expresso'){
            $frete = 'Expresso';
        }
        else if ($this->tipo_frete === 'retirar_loja'){
            $frete = 'Econômico';
        }
        else{
            $frete = 'Frete não selecionado';
        }
        return $frete;
    }

    public function url()
    {
        if ($this->franquia?->tipo_franqueado != 'toy') {
            $url = 'https://facilitrip.com.br';
        } else {
            if($this->franquia?->subdominio){
                $subdominio = $this->franquia?->subdominio;
            }
            else{
                $subdominio = 'facilitoy';
            }
            // $url = 'https://' . $subdominio .'.facilitrip.com.br';
            if (app()->environment('production')) {
                $url = 'https://' . $subdominio . '.facilitoy.com.br';
            } else {
                $url = 'https://' . $subdominio . '.facilitrip.com.br';
            }
        }

        if ($url){
            $url = $url . '/pagamento/dados/' . $this->token;
        }
        else{
            $url = '';
        }
        
        return $url;
    }



    // public function item()
    // {
    //     return $this->hasOne(ItensPedidos::class, 'id_pedido', 'id');
    // }
}
