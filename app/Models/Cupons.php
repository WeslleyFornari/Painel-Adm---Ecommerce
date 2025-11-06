<?php

namespace App\Models;

use App\Scopes\EmpresaScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cupons extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cupons';

    protected $fillable = [

        'codigo',
        'tipo',
        'tipo_franqueado',
        'modalidade',
        'qtd',
        'valor_minimo',
        'status',
        'valor',
        'id_franquia'
    ];

    public function franquia()
    {
        return $this->hasOne(Franquias::class, 'id', 'id_franquia');
    }


    public function scopeEmpresa($query)
    {
        return $query->where('id_empresa', '=', Auth::user()->id_empresa);
    }

    public function pedidos()
    {
        return  $this->hasMany(Pedidos::class, 'id_cupom', 'id');
    }
    public function cuponsDisponiveis()
    {
        $usosRealizados = $this->hasMany(Pedidos::class, 'id_cupom', 'id')
            ->where('id_status', '>=', '3')->where('id_status', '!=', '10')
            ->count();
        return max(0, $usosRealizados);
    }
    public function cuponsUsos()
    {
        $usosRealizados = $this->hasMany(Pedidos::class, 'id_cupom', 'id')
            ->where('id_status', '>=', '3')->where('id_status', '!=', '10')
            ->count();
        $usosRestantes = intval($this->qtd) - $usosRealizados;
        return max(0, $usosRestantes);
    }
    public function produto()
    {
        return $this->hasOne(Produtos::class, 'id', 'id_produto');
    }


    // Calculo de desconto - CUPOM

    public function calculaDesconto()
    {

        $valorProduto = $this->produto->valor;

        if ($this->tipo == "porcentagem") {
            $valorFinal = $valorProduto - ($valorProduto * ($this->valor / 100));
        }
        if ($this->tipo == "real") {
            $valorFinal = $valorProduto - $this->valor;
        }
        return $valorFinal;
    }
}
