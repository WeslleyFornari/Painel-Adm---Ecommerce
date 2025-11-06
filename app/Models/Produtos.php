<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Produtos extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'produtos';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nome',
        'slug',
        'marca',
        'id_franquia',
        'modalidade',
        'tipo',
        'id_categoria',
        'descricao',
        'valor_base_diaria',
        'tempo_entrega',
        'peso_maximo',
        'idade',
        'orientacoes',
        'id_produto_relacionado',
        'id_produto_recomendado',
        'produto_catalogo',
        'status',
    ];

    public function pedidos(){
        return $this->hasMany(ItensPedidos::class, 'id_produto');
    }

    public function franquia(){
        return $this->belongsTo(Franquias::class,'id_franquia','id');
    }

    public function categoria2()
    {
        return $this->belongsTo(ProdutoCategoria::class, 'categoria_id');
    }

    public function categoria(){
        return $this->hasOne(ProdutoCategoria::class,'id','id_categoria');
    }

    public function caracteristica(){
        return $this->hasMany(ProdutosCaracteristicas::class, 'id_produto');
    }
    public function promocoes(){
        return $this->hasMany(Promocoes::class, 'id_produto');
    }
    public function produtos_relacionados(){
        return $this->hasMany(Produtos::class, 'id_produto_relacionado');
    }
    public function avaliacao(){
        return $this->hasMany(Avaliacoes::class, 'id_produto')->orderBy('created_at','desc');
    }
    public function foto(){
        return $this->hasMany(ProdutosFotos::class, 'id_produto')->orderBy('ordem','asc');
    }

    public function fotoPrincipal()
    {
        return $this->hasOne(ProdutosFotos::class, 'id_produto')->where('ordem', 0);
    }
    public function video(){
        return $this->hasMany(ProdutosVideos::class, 'id_produto');
    }
    public function marca()
    {
        return $this->hasOne(Marca::class, 'id', 'marca_id');
    }
    public function valores_periodos()
    {
        return $this->hasMany(PeriodosValores::class, 'id_produto')
                    ->join('periodos', 'periodos_valores.id_periodo', '=', 'periodos.id')
                    ->orderBy('periodos.dias', 'asc');
    }

    public function valor_periodo($id) {
        $valor = $this->hasOne(PeriodosValores::class, 'id_produto')
                      ->where('id_periodo', $id)
                      ->first();
    
        if ($valor) {
            return $valor;
        } else {
            return null;
        }
    }
    
/*
    public function midiaDinamica($collum){
        return $this->hasOne(Media::class,'id',$collum)->first();
    }
        */
    public function perguntas(){
        return $this->hasMany(PerguntasFrequentes::class, 'id_produto');
    }

    public function favorito(){
        if (Auth::check()){
            return $this->hasOne(Favoritos::class,'id_produto')->where('id_user', Auth::user()->id)->first();
        }
        else{
            return null;
        }
    }
    public function estoque(){
        return $this->hasOne(Estoque::class,'id_produto','id');
    }
   
    public function estoques(){
        return $this->hasMany(Estoque::class, 'id_produto');
    }
    public function recomendado()
{
    return $this->hasOne(Produtos::class, 'id', 'id_produto_recomendado');
}

// Weslley
    public function img(){
        return $this->hasOne(ProdutosFotos::class,'id_produto','id');
    }

    public function getOptions()
    {
        return Produtos::all()->map(function ($produto) {
            return [
                'id' => $produto->id,
                'label' => $produto->nome,
                'value' => $produto->id
            ];
        })->toArray();
    }

    public static function getMerchantProducts()
    {
        $listMerchant = [];
        $products = Produtos::with('fotoPrincipal')->get();

        foreach ($products as $product) {

            if ($product->status == 'inativo') {
                continue;
            }

            $listMerchant[] = [
                'id' => $product->id,
                'title' => $product->nome,
                'description' => strip_tags($product->descricao),
                'link' => url('/produtos/detalhes/' . $product->slug),
                'image_link' => url('/uploads/' . $product->fotoprincipal?->imagem->file),
                'price' => $product->valor_base_diaria,
                'brand' => $product->marca,
                'in_stock' => true,
            ];
        }

        return $listMerchant;
    }

    public function link()
    {
        $dominio = 'facilitrip.com.br';
        if ($this->tipo == 'toy') {
            $dominio = 'facilitoy.com.br';
        }
      
                $subdominio = $this->franquia ?  $this->franquia->subdominio .'.' : '';
 
            if ($this->modalidade != 'alugar'){
                $url = 'https://' .  $subdominio . $dominio.'/produtos/detalhes_alugar/' . $this->slug;
            }
            else{
                $url = 'https://' .  $subdominio . $dominio.'./produtos/detalhes/' . $this->slug;
            }


        if (!$url){
            $url = '';
        }
        
        return $url;
    }
}
