<?php

namespace App\Providers;

use App\Models\Configuracoes;
use App\Models\FormularioFranquia;
use App\Models\Localizacao;
use App\Models\Produtos;
use App\Models\RegiaoAtendida;
use App\Models\ProdutoCategoria;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Session;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        // Define the 'whereLike' macro
        Builder::macro('whereLike', function ($attributes, string $searchTerm) {
            return $this->where(function (Builder $query) use ($attributes, $searchTerm) {
                foreach (Arr::wrap($attributes) as $attribute) {
                    $query->when(
                        // Check if the attribute is not an expression and contains a dot (indicating a related model)
                        ! ($attribute instanceof \Illuminate\Contracts\Database\Query\Expression) &&
                        str_contains((string) $attribute, '.'),
                        function (Builder $query) use ($attribute, $searchTerm) {
                            // Split the attribute into a relation and related attribute
                            [$relation, $relatedAttribute] = explode('.', (string) $attribute);

                            // Perform a 'LIKE' search on the related model's attribute
                            $query->orWhereHas($relation, function (Builder $query) use ($relatedAttribute, $searchTerm) {
                                $query->where($relatedAttribute, 'LIKE', "%{$searchTerm}%");
                            });

                            // if need more deep nesting then commonet above code and 
                            // use below (which is not recommend)
                            // Split the attribute into a relation and related attribute
                            // $attrs = explode('.', (string) $attribute);
                            // $relatedAttribute = array_pop($attrs);
                            // $relation = implode('.', $attrs);

                            // Perform a 'LIKE' search on the related model's attribute
                            // $query->orWhereRelation($relation, $relatedAttribute, 'LIKE', "%{$searchTerm}%");
                        },
                        function (Builder $query) use ($attribute, $searchTerm) {
                            // Perform a 'LIKE' search on the current model's attribute
                            // also attribute can be an expression
                            $query->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
                        }
                    );
                }
            });
        });


        Paginator::useBootstrap();
        /*
        $empresa_id = Auth::user()->id_empresa;
        
        $produtos = Produtos::where('id_empresa', $empresa_id)->get();


        View::share('produtos',$produtos);*/
       $regioesAtendidas = RegiaoAtendida::where('status','ativo')->get()->groupBy(function($q){
        return $q->cidade . ', ' . $q->estado;
       });

       $cidades = RegiaoAtendida::where('status', 'ativo')->select('cidade')->distinct()->pluck('cidade')->toArray();

       $categorias = ProdutoCategoria::select()->distinct()->get();
       
       View::share([
            'regioesAtendidas' => $regioesAtendidas,
            'cidades' => $cidades,
            'categorias' => $categorias,
        ]);
       
       view()->share('estados', FormularioFranquia::$estados);
    }


    
}
