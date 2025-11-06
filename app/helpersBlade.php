<?php

use App\Models\Configuracoes;
use App\Models\EntregaPedido;
use App\Models\EntregaProduto;
use App\Models\Estoque;
use App\Models\ItensPedidos;
use App\Models\Pedidos;
use App\Models\Produtos;
use App\Models\RegiaoAtendida;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

if (! function_exists('getMoney')) {

	function getMoney($value,$moeda = null){
		if($value === null){
			return '0,00';
		}
		if($moeda !== null){
			return $moeda . " " . number_format($value,2,',','.');
		}else{
			return @number_format($value,2,',','.');
		}
		
	}
}
if (! function_exists('saveMoney')) {
	function saveMoney($value){
		
		if($value === null){
			return 0.00;
		}
		$money = str_replace(".", "", $value);
		$money = str_replace(",", ".", $money);
		return $money;
	}
}
if (! function_exists('progressColor')) {
	function progressColor($value){
		if($value < 15){
			return 'bg-danger';
		}
		if($value >= 15 && $value <= 30){
			return 'bg-warning';
		}

		if($value > 30 && $value <= 70){
			return 'bg-primary';
		}

		if($value > 70 ){
			return 'bg-success';
		}
		
	}
}
if (! function_exists('formatNameConta')) {
	function formatNameConta($value){
		 $arrayContas = [
                    "dinheiro"=>'Dinheiro',
                    "cartao_credito"=>'Cartão',
                    "bancaria"=>'Conta',
                    "investimento"=>'Investimento',
            ];
        return data_get($arrayContas,$value,'');
		
	}
}
if (! function_exists('slug')) {
	function slug($value){
		 $slug = Str::slug($value, '-');
        return $slug;
		
	}
}
if (! function_exists('limit_text')) {
	
	function limit_text($value,$limit = 20){
		 $slug = Str::limit($value, $limit);
		 if(Str::length($value) > 20){
        return '<span title="'.$value.'">' . $slug . '</span>' ;
	}else{
		return $value;
	}
		
	}
}
if (! function_exists('status')) {
	function status($value){
		if($value){
		$array = [
			'ativo'=>
				[
					'title'=>'Ativo',
					'color'=> 'text-bg-success',
				],
			'inativo'=>
				[
					'title'=>'Inativo',
					'color'=> "text-bg-warning",
				],
			'removed'=>
				[
					'title'=>'Removido',
					'color'=> "text-bg-danger"
				]
			];
		return '<span class="badge '.$array[$value]['color'].'">'.$array[$value]['title'].'</span>';
		;
		}
	}
}

if (! function_exists('calcParcelaJuros')) {
	function calcParcelaJuros($valor_total,$parcelas = null,$juros=0){
		if($parcelas){
			if($juros==0){
				$string = number_format($valor_total/$parcelas, 2, ",", ".");	
			return $string;
			}else{
				/*
					$I = $juros/100.00;
					$valor_parcela = $valor_total*$I*pow((1+$I),$parcelas)/(pow((1+$I),$parcelas)-1);
					$string = number_format($valor_parcela, 2, ",", ".");
				*/
				$conta = $valor_total * ($juros / 100);
				$conta = ($valor_total + $conta) / $parcelas;

			return number_format($conta, 2, ",", ".");	
			}
		}else{
			return '0,00';
		}
     }

	 if (! function_exists('replaceTerms')) {
	 function replaceTerms($text)
    {
        $user = Auth::user();
		$terms = [
			'@nome' => $user->first_name,
		];
        if ($user) {
			foreach ($terms as $index => $term) {
				
				$text = str_replace($index, $term, $text);
			}

            return $text;
        }

        return $text;
    }
}
if (! function_exists('createDirecrotory')) {
	function  createDirecrotory($folder){
		$path = public_path().'/'.$folder;
		File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
	}
}
if (! function_exists('termosReplace')) {
	function  termosReplace($value){
		$array = [
			'dizimo' 		=> 'Dízimo',
			'oferta' 		=> 'Oferta',
			'presencial'	=> 'Presencial',
			'fisico'		=> 'Físico',
			'online'		=> 'Online',
		];

		return $array[$value] ?? $value;
	}
}

if (! function_exists('entrega')) {
	function  entrega($value){
		$entrega = EntregaPedido::where('id', $value)->first();

		$data_entrega = Carbon::parse($entrega->data_entrega);
		$data_devolucao = Carbon::parse($entrega->data_devolucao);

		$tempo = $data_entrega->diffInDays($data_devolucao);
		return $tempo;
	}
}
if (! function_exists('entrega_carrinho')) {
    function entrega_carrinho($endereco){
        $franquia = localizacao()->franquia_id;
        $bairro = str_replace('Loteamento ', '', $endereco->bairro);
        $bairro = '%' . $bairro . '%';
        $cidade = '%' . $endereco->cidade . '%';
        $regiao = RegiaoAtendida::where('id_franqueado', $franquia)
            ->where('bairro', 'like', $bairro)
            ->where('cidade', 'like', $cidade)
            ->first();
        if ($regiao){
            return $regiao;
        }
    }
}
if (!function_exists('estoque_disponivel')) {
    function estoque_disponivel($pedidoId, $itemId) {
        $pedido = Pedidos::find($pedidoId);
        $item = ItensPedidos::find($itemId);
        $entrega = EntregaPedido::where('id_itens_pedido', $itemId)->first();
        $produto = Produtos::withTrashed()->find($item->id_produto);
        $availableProducts = Estoque::where('status', 'Disponível')
		  
            ->where('id_produto', $produto->id)
			->where('id_franqueado', $pedido->id_franquia)
            ->whereDoesntHave('alugueis', function ($query) use ($entrega) {
                $startDate = Carbon::parse($entrega->data_entrega);
                $endDate = Carbon::parse($entrega->data_devolucao);
                $query->where(function ($query) use ($startDate, $endDate) {
                    $query->where('data_entrega', '<=', $endDate)
                          ->where('data_devolucao', '>=', $startDate);
                });
            })
            ->get();
        return $availableProducts;
    }
}
if (!function_exists('conf')) {
    function conf($param) {
		$config = Configuracoes::where('tipo_franqueado', 'trip')->where('param', $param)->first();
	
		if ($config) {
			return $config->value;
		}
	
		return null;
	}
}

if (!function_exists('formatPhoneNumber')) {
    function formatPhoneNumber($phone) {
        $phone = preg_replace('/\D/', '', $phone);
        
        if (strlen($phone) === 11) {
            return '55' . $phone;
        }
        return null; 
    }
}



}
?>