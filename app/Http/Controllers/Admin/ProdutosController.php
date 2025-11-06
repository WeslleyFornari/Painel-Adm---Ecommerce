<?php

namespace App\Http\Controllers\Admin;

use App\Models\PeriodosValores;
use App\Models\ProdutoCategoria;
use App\Models\ProdutosCaracteristicas;
use App\Models\ProdutosFotos;
use App\Models\ProdutosVideos;
use App\Models\Promocoes;
use App\Models\Franquias;
use App\Models\Marca;
use Illuminate\Http\Request;
use App\Models\Produtos;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProdutosController extends Controller
{

    public function index(Request $request)
    {
        $data = $request->all();
        $franquias = Franquias::where('tipo_franqueado', 'toy')->get()->toArray();

        $franquia = $request->input('franquia');
        $unidade = $request->input('unidade');
        $termo = $request->input('termo');

        $query = Produtos::query();

        if (Auth::user()->role == 'franqueado') {

            if (Auth::user()->franquia->tipo_franqueado == 'toy') {
                $query->where('id_franquia', Auth::user()->id_franquia);
            } elseif ($franquia == 'trip') {
                $query->whereHas('id_franquia', function ($q) {
                    $q->where('tipo_franqueado', 'trip');
                });
            }
        } else {

            if ($franquia == 'toy') {
                $query->where('id_franquia', $unidade);
            } elseif ($franquia == 'trip') {
                $query->where('tipo', 'trip');
            } else {
                $query = Produtos::query();
            }
        }

        if ($request->filled('termo')) {

            $query->whereRaw("LOWER(nome) LIKE ?", ['%' . strtolower($termo) . '%']);
        }
        
        $produtos = $query->where('produto_catalogo', 'nao')->orderBy('created_at', 'desc')->paginate(10);
       

        if ($request->ajax()) {

            return view('admin.produtos._list-produtos', compact('produtos',))->render();
        }

        return view('admin.produtos.index', compact('produtos', 'franquias'));
    }

    public function catalogo(Request $request)
    {
        $data = $request->all();
        $franquia = $request->input('franquia');
        $query = Produtos::query();

        if (Auth::user()->role == 'franqueado') {
            if (Auth::user()->franquia->tipo_franqueado == 'toy') {
                $query->where('id_franquia', Auth::user()->id_franquia)->paginate(10);
            } else {
                $query->where('tipo', Auth::user()->franquia->tipo_franqueado)->paginate(10);
            }
        } else {

            if ($franquia == 'trip') {

                $query->where('tipo', 'trip')->get();
            } elseif ($franquia == 'toy') {

                $query->where('tipo', 'toy')->get();
            }

            if ($request->has('termo')) {

                $termo = $request->input('termo');
                $query->where('nome', 'LIKE', "%$termo%");
            }
        }

        $produtos = $query->where('produto_catalogo', 'sim')->paginate(5);

        if ($request->ajax()) {

            return view('admin.produtos.produtos_catalogo._list-produtos', compact('produtos'))->render();
        }

        return view('admin.produtos.produtos_catalogo.index', compact('produtos'));
    }

    public function newCatalogo(Request $request)
    {
        $marcas = Marca::all();
        $categorias_trip = ProdutoCategoria::where('tipo', 'trip')->select()->distinct()->get();
        $categorias_toy = ProdutoCategoria::where('tipo', 'toy')->select()->distinct()->get();
        $produto = null;
        $produtos_catalogo = Produtos::where('status', 'ativo')->where('produto_catalogo', 'sim')->get();

        if (Auth::user()->role == 'franqueado') {
            $produtos_catalogo = Produtos::where('produto_catalogo', 'sim')->where('tipo', Auth::user()->franquia->tipo_franqueado)->get();
        } else {
            $produtos_catalogo = Produtos::where('produto_catalogo', 'sim')->get();
        }

        $produtos = Produtos::all();
        $selecionarFranquia = Franquias::where('status', 'ativo')->where('status', 'ativo')->where('tipo_franqueado', 'toy')->get();
        $idades =  Produtos::select('idade')->distinct('idade')->get()->pluck('idade')->toArray();
        $catalogo = true;
        $periodos = null;
        return view('admin.produtos.new', compact('periodos', 'produtos', 'produtos_catalogo', 'categorias_trip', 'categorias_toy', 'produto', 'selecionarFranquia', 'idades', 'marcas', 'catalogo'));
    }

    public function new(Request $request)
    {
        $marcas = Marca::all();
        $categorias_trip = ProdutoCategoria::where('tipo', 'trip')->select()->distinct()->get();
        $categorias_toy = ProdutoCategoria::where('tipo', 'toy')->select()->distinct()->get();
        $produto = null;
        $produtos = Produtos::all();
        $produtos_catalogo = Produtos::where('status', 'ativo')->where('produto_catalogo', 'sim')->get();
        $selecionarFranquia = Franquias::where('status', 'ativo')->where('status', 'ativo')->where('tipo_franqueado', 'toy')->get();
        if (Auth::user()->role == 'franqueado') {
            $produtos_catalogo = Produtos::where('produto_catalogo', 'sim')->where('tipo', Auth::user()->franquia->tipo_franqueado)->get();
        } else {
            $produtos_catalogo = Produtos::where('produto_catalogo', 'sim')->get();
        }
        $selecionarFranquia = Franquias::where('status', 'ativo')->where('tipo_franqueado', 'toy')->get();
        $idades =  Produtos::select('idade')->distinct('idade')->get()->pluck('idade')->toArray();
        $catalogo = false;
        $periodos = Auth::user()->franquia?->periodos;
        return view('admin.produtos.new', compact('periodos', 'produtos', 'produtos_catalogo', 'categorias_trip', 'categorias_toy', 'produto', 'selecionarFranquia', 'idades', 'marcas', 'catalogo'));
    }


    public function store(Request $request)
    {
        $data = $request->except('_token');

        if (!$request->filled('catalogo')) {
            $catalogo = 'nao';
        } else {
            $catalogo = $data['catalogo'];
        }

        $fields = [
            'nome' => 'Nome não preenchido.',
            'categoria' => 'Categoria não selecionada',
            'descricao' => 'Descrição não preenchida.',
            'marca' => 'Marca não selecionada',
        ];

        if ($data['tipo'] == 'trip' && $catalogo != 'sim') {
            $fields['valor_base_diaria'] = 'Valor não preenchido';
        } else if ($data['tipo'] == 'toy' && $catalogo != 'sim') {
            $fields['modalidade'] = 'Modalidade não preenchida';
            $fields['id_franqueado'] = 'Franquia não selecionada';
        }


        foreach ($fields as $field => $message) {
            if ($field == 'categoria') {
                $field = $field . '_' . $data['tipo'];
                if (!$request->filled($field)) {
                    return response()->json([
                        'campo' => $field,
                        'msg' => $message,
                        'status' => 422
                    ], 422);
                }
            } else {
                if (!$request->filled($field)) {
                    return response()->json([
                        'campo' => $field,
                        'msg' => $message,
                        'status' => 422
                    ], 422);
                }
            }
        }

        if ($data['tipo'] == 'toy' && $catalogo != 'sim') {

            if ($data['modalidade'] == 'vender' ||  $data['modalidade'] == 'alugar_vender') {
                if (!$request->filled('valor_base_diaria')) {
                    return response()->json([
                        'campo' => 'valor_base_diaria',
                        'msg' => 'Valor não preenchido',
                        'status' => 422
                    ], 422);
                }

                $data['valor_base_diaria'] = str_replace(',', '.', str_replace('.', '', $data['valor_base_diaria']));
            }
            if ($data['modalidade'] == 'alugar' ||  $data['modalidade'] == 'alugar_vender') {
                $valores_peridos = $request->input('valores_periodos');
                if ($valores_peridos) {
                    foreach ($valores_peridos['id_periodo'] as $index => $id_periodo) {
                        $campo = 'valores_periodos[valor][' . $id_periodo . ']';
                        if (!$valores_peridos['valor'][$id_periodo]) {
                            return response()->json([
                                'campo' => $campo,
                                'msg' => 'Valor não preenchido',
                                'status' => 422
                            ], 422);
                        }
                    }
                }
            }
        }

        $ordem = $request->input('ordem');
        $id_media = $request->input('id_media');

        if (!$ordem) {
            return response()->json([
                'campo' => 'foto',
                'msg' => 'Adicione ao menos uma foto ao produto para continuar',
                'status' => 422
            ], 422);
        }

        if ($data['tipo'] == 'trip') {
            if ($catalogo != 'sim') {
                $data['valor_base_diaria'] = str_replace(',', '.', str_replace('.', '', $data['valor_base_diaria']));
            }

            $produto = Produtos::create([
                'nome' => $data['nome'],
                'descricao' => $data['descricao'],
                'id_categoria' => $data['categoria_trip'],
                'modalidade' => 'alugar',
                'marca' => $data['marca'],
                'valor_base_diaria' => $data['valor_base_diaria'],
                'status' => 'ativo',
                'idade' => $data['idade'],
                'orientacoes' => $data['orientacoes'],
                'peso_maximo' => $data['peso_maximo'],
                'id_produto_recomendado' => $data['id_produto_recomendado'] ?? null,
                'slug' => 'novo',
                'produto_catalogo' => $catalogo ?? 'nao',
                'tipo' => "trip",
            ]);
        } else if ($data['tipo'] == 'toy') {
            $produto = Produtos::create([
                'nome' => $data['nome'],
                'descricao' => $data['descricao'],
                'id_franquia' => $data['id_franqueado'],
                'id_categoria' => $data['categoria_toy'],
                'tipo' => 'toy',
                'modalidade' => $data['modalidade'],
                'marca' => $data['marca'],
                'status' => 'ativo',
                'idade' => $data['idade'],
                'orientacoes' => $data['orientacoes'],
                'peso_maximo' => $data['peso_maximo'],
                'id_produto_recomendado' => $data['id_produto_recomendado'] ?? null,
                'produto_catalogo' => $catalogo ?? 'nao',
                'slug' => 'novo',
                'valor_base_diaria' => $data['valor_base_diaria'] ?? null,
            ]);

            if ($produto->modalidade == 'alugar' || $produto->modalidade == 'alugar_vender') {
                $valores_peridos = $request->input('valores_periodos');
                if ($valores_peridos) {
                    foreach ($valores_peridos['id_periodo'] as $index => $id_periodo) {
                        $valor = new PeriodosValores();
                        $valores_peridos['valor'][$id_periodo] = str_replace(',', '.', str_replace('.', '', $valores_peridos['valor'][$id_periodo]));
                        $valor->id_produto = $produto->id;
                        $valor->id_periodo = $id_periodo;
                        $valor->valor_periodo = $valores_peridos['valor'][$id_periodo];
                        $valor->save();
                    }
                }
            }
        }

        $slug = Str::slug($data['nome'], '-') . '-' . $produto->id;
        $produto->update([
            'slug' => $slug,
        ]);
        if ($ordem) {

            $cont = 0;

            foreach ($ordem as $index => $ordemItem) {
                $foto = new ProdutosFotos();
                $foto->id_produto = $produto->id;
                $foto->ordem = $cont;
                $foto->id_media = $id_media[$index];
                $foto->status = 'ativo';
                $foto->save();

                $cont++;
            }
        }

        $caracteristicas = $request->input('caracteristicas');

        if ($caracteristicas) {
            foreach ($caracteristicas['titulo'] as $index => $titulo) {
                if (!empty($titulo) && !empty($caracteristicas['descricao'][$index])) {
                    $caracteristica = new ProdutosCaracteristicas();
                    $caracteristica->id_produto = $produto->id;
                    $caracteristica->titulo = $titulo;
                    $caracteristica->descricao = $caracteristicas['descricao'][$index];
                    $caracteristica->status = 'ativo';
                    $caracteristica->save();
                }
            }
        }

        $videos = $request->input('videos');

        if ($videos) {

            foreach ($videos['ordem'] as $index => $ordem) {
                $video = new ProdutosVideos();
                $video->id_produto = $produto->id;
                $video->ordem = $ordem;
                $video->url = $videos['url'][$index];
                $video->status = 'ativo';
                $video->save();
            }
        }

        $promocoes = $request->input('promocoes');

        if (!empty($promocoes) && isset($promocoes['desconto'])) {
            foreach ($promocoes['desconto'] as $index => $desconto) {
                if (
                    !empty($desconto) &&
                    !empty($promocoes['de'][$index]) &&
                    !empty($promocoes['ate'][$index]) &&
                    !empty($promocoes['tipo'][$index])
                ) {
                    $promocao = new Promocoes();
                    $promocao->id_produto = $produto->id;
                    $promocao->desconto = $desconto;
                    $promocao->de = $promocoes['de'][$index];
                    $promocao->ate = $promocoes['ate'][$index];
                    $promocao->tipo = $promocoes['tipo'][$index];
                    $promocao->save();
                }
            }
        }

        return response()->json(['status' => 'ok'], 200);
    }
    public function edit(Request $request, $id)
    {
        $marcas = Marca::all();
        $produtos = Produtos::all();
        $produto = Produtos::find($id);
        if (Auth::user()->role == 'franqueado') {
            $produtos_catalogo = Produtos::where('produto_catalogo', 'sim')->where('tipo', Auth::user()->franquia->tipo_franqueado)->get();
        } else {
            $produtos_catalogo = Produtos::where('produto_catalogo', 'sim')->get();
        }
        $selecionarFranquia = Franquias::where('status', 'ativo')->where('tipo_franqueado', 'toy')->get();
        $idades = Produtos::select('idade')->distinct('idade')->get()->pluck('idade')->toArray();
        $categorias_trip = ProdutoCategoria::where('tipo', 'trip')->select()->distinct()->get();
        $categorias_toy = ProdutoCategoria::where('tipo', 'toy')->select()->distinct()->get();
        $catalogo = false;
        if ($produto->produto_catalogo === 'sim') {
            $catalogo = true;
        } else if ($produto->produto_catalogo === 'nao') {
            $catalogo = false;
        }
        $periodos = $produto->franquia->periodos ?? Auth::user()->franquia->periodos;

        return view('admin.produtos.edit', compact('periodos', 'produto', 'produtos_catalogo', 'selecionarFranquia', 'categorias_trip', 'categorias_toy', 'produtos', 'idades', 'marcas', 'catalogo'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->except(['_token', '_method']);

        if (!$request->filled('catalogo')) {
            $catalogo = 'nao';
        } else {
            $catalogo = $data['catalogo'];
        }

        $produto = Produtos::find($id);

        $slug = Str::slug($data['nome'], '-');

        $data = $request->except('_token');

        $produto->caracteristica()->delete();
        $produto->foto()->delete();
        $produto->video()->delete();
        $produto->promocoes()->delete();
        $produto->valores_periodos()->delete();

        $fields = [
            'nome' => 'Nome não preenchido.',
            'categoria' => 'Categoria não selecionada',
            'descricao' => 'Descrição não preenchida.',
            'marca' => 'Marca não selecionada',
        ];

        if ($data['tipo'] == 'trip' && $catalogo != 'sim') {
            $fields['valor_base_diaria'] = 'Valor não preenchido';
        } else if ($data['tipo'] == 'toy' && $catalogo != 'sim') {
            $fields['modalidade'] = 'Modalidade não preenchida';
            $fields['id_franqueado'] = 'Franquia não selecionada';
        }


        foreach ($fields as $field => $message) {
            if ($field == 'categoria') {
                $field = $field . '_' . $data['tipo'];
                if (!$request->filled($field)) {
                    return response()->json([
                        'campo' => $field,
                        'msg' => $message,
                        'status' => 422
                    ], 422);
                }
            } else {
                if (!$request->filled($field)) {
                    return response()->json([
                        'campo' => $field,
                        'msg' => $message,
                        'status' => 422
                    ], 422);
                }
            }
        }

        if ($data['tipo'] == 'toy' && $catalogo != 'sim') {

            if ($data['modalidade'] == 'vender' ||  $data['modalidade'] == 'alugar_vender') {
                if (!$request->filled('valor_base_diaria')) {
                    return response()->json([
                        'campo' => 'valor_base_diaria',
                        'msg' => 'Valor não preenchido',
                        'status' => 422
                    ], 422);
                }

                $data['valor_base_diaria'] = str_replace(',', '.', str_replace('.', '', $data['valor_base_diaria']));
            }
            if ($data['modalidade'] == 'alugar' ||  $data['modalidade'] == 'alugar_vender') {
                $valores_peridos = $request->input('valores_periodos');
                if ($valores_peridos) {
                    foreach ($valores_peridos['id_periodo'] as $index => $id_periodo) {
                        $campo = 'valores_periodos[valor][' . $id_periodo . ']';
                        if (!$valores_peridos['valor'][$id_periodo]) {
                            return response()->json([
                                'campo' => $campo,
                                'msg' => 'Valor não preenchido',
                                'status' => 422
                            ], 422);
                        }
                    }
                }
            }
        }

        $ordem = $request->input('ordem');
        $id_media = $request->input('id_media');

        if (!$ordem) {
            return response()->json([
                'campo' => 'foto',
                'msg' => 'Adicione ao menos uma foto ao produto para continuar',
                'status' => 422
            ], 422);
        }

        if ($data['tipo'] == 'trip') {
            if ($catalogo != 'sim') {
                $data['valor_base_diaria'] = str_replace(',', '.', str_replace('.', '', $data['valor_base_diaria']));
            }
            $produto->update([
                'nome' => $data['nome'],
                'descricao' => $data['descricao'],
                'id_categoria' => $data['categoria_trip'],
                'modalidade' => 'alugar',
                'marca' => $data['marca'],
                'valor_base_diaria' => $data['valor_base_diaria'],
                'status' => 'ativo',
                'idade' => $data['idade'],
                'orientacoes' => $data['orientacoes'],
                'peso_maximo' => $data['peso_maximo'],
                'id_produto_recomendado' => $data['id_produto_recomendado'] ?? null,
                'slug' => "$slug-$id",
                'produto_catalogo' => $catalogo ?? 'nao',
                'tipo' => "trip",
            ]);
        } else if ($data['tipo'] == 'toy') {
            if ($data['modalidade'] == 'vender' || $data['modalidade'] == 'alugar_vender') {
                $data['valor_base_diaria'] = str_replace(',', '.', str_replace('.', '', $data['valor_base_diaria']));
            }
            else{
                $data['valor_base_diaria'] = '00.00';
            }
            $produto->update([
                'nome' => $data['nome'],
                'descricao' => $data['descricao'],
                'id_franquia' => $data['id_franqueado'],
                'id_categoria' => $data['categoria_toy'],
                'tipo' => 'toy',
                'modalidade' => $data['modalidade'],
                'marca' => $data['marca'],
                'status' => 'ativo',
                'idade' => $data['idade'],
                'orientacoes' => $data['orientacoes'],
                'peso_maximo' => $data['peso_maximo'],
                'id_produto_recomendado' => $data['id_produto_recomendado'] ?? null,
                'produto_catalogo' => $catalogo ?? 'nao',
                'slug' => "$slug-$id",
                'valor_base_diaria' => $data['valor_base_diaria'],
            ]);

            if ($data['modalidade'] == 'alugar' || $data['modalidade'] == 'alugar_vender') {
                $valores_peridos = $request->input('valores_periodos');
                if ($valores_peridos) {
                    foreach ($valores_peridos['id_periodo'] as $index => $id_periodo) {
                        $valor = new PeriodosValores();
                        $valores_peridos['valor'][$id_periodo] = str_replace(',', '.', str_replace('.', '', $valores_peridos['valor'][$id_periodo]));
                        $valor->id_produto = $produto->id;
                        $valor->id_periodo = $id_periodo;
                        $valor->valor_periodo = $valores_peridos['valor'][$id_periodo];
                        $valor->save();
                    }
                }
            }
        }

        $caracteristicas = $request->input('caracteristicas');

        if ($caracteristicas) {
            foreach ($caracteristicas['titulo'] as $index => $titulo) {
                if (!empty($titulo) && !empty($caracteristicas['descricao'][$index])) {
                    $caracteristica = new ProdutosCaracteristicas();
                    $caracteristica->id_produto = $produto->id;
                    $caracteristica->titulo = $titulo;
                    $caracteristica->descricao = $caracteristicas['descricao'][$index];
                    $caracteristica->status = 'ativo';
                    $caracteristica->save();
                }
            }
        }

        if ($ordem) {

            $cont = 0;

            foreach ($ordem as $index => $ordemItem) {
                $foto = new ProdutosFotos();
                $foto->id_produto = $produto->id;
                $foto->ordem = $cont;
                $foto->id_media = $id_media[$index];
                $foto->status = 'ativo';
                $foto->save();

                $cont++;
            }
        }

        $videos = $request->input('videos');

        if ($videos) {

            foreach ($videos['ordem'] as $index => $ordem) {
                $video = new ProdutosVideos();
                $video->id_produto = $produto->id;
                $video->ordem = $ordem;
                $video->url = $videos['url'][$index];
                $video->status = 'ativo';
                $video->save();
            }
        }

        $promocoes = $request->input('promocoes');

        if (!empty($promocoes) && isset($promocoes['desconto'])) {
            foreach ($promocoes['desconto'] as $index => $desconto) {
                if (
                    !empty($desconto) &&
                    !empty($promocoes['de'][$index]) &&
                    !empty($promocoes['ate'][$index]) &&
                    !empty($promocoes['tipo'][$index])
                ) {
                    $promocao = new Promocoes();
                    $promocao->id_produto = $produto->id;
                    $promocao->desconto = $desconto;
                    $promocao->de = $promocoes['de'][$index];
                    $promocao->ate = $promocoes['ate'][$index];
                    $promocao->tipo = $promocoes['tipo'][$index];
                    $promocao->save();
                }
            }
        }

        return response()->json(['status' => 'ok'], 200);
    }

    public function mudarStatus($id = null)
    {
        $produto = Produtos::find($id);
        if ($produto->status == 'ativo') {
            $produto->status = 'inativo';
        } else {
            $produto->status = 'ativo';
        }

        $produto->save();

        return response()->json(['status' => 'ok']);
    }

    public function mudarStatusDetalhes($id = null)
    {
        $produtodetalhes = ProdutosCaracteristicas::find($id);

        if (!$produtodetalhes) {
            $produtodetalhes = ProdutosFotos::find($id);
            if (!$produtodetalhes) {
                $produtodetalhes = ProdutosVideos::find($id);
            }
        }
        if ($produtodetalhes->status == 'ativo') {
            $produtodetalhes->status = 'inativo';
        } else {
            $produtodetalhes->status = 'ativo';
        }

        $produtodetalhes->save();

        return response()->json(['status' => 'ok']);
    }

    public function delete(Request $request, $id)
    {
        $produto = Produtos::find($id);

        if (!$produto) {
            return response()->json(['status' => 'error', 'message' => 'Produto não encontrado.'], 404);
        }

        DB::beginTransaction();

        // if ($produto->pedidos()->exists()) {
        //     $produto->status = 'inativo';
        //     $produto->save();
        //     $produto->estoques()->update(['status' => 'Indisponível']);

        // DB::commit();
        //     return response()->json(['status' => 'error', 'message' => 'Produto associado a pedidos. Ele foi inativado e não pode ser deletado.']);
        // }

        if (Auth::user()->role == 'admin') {
            $produto->valores_periodos()->delete();
            $produto->caracteristica()->delete();
            $produto->foto()->delete();
            $produto->video()->delete();
            $produto->promocoes()->delete();
            $produto->estoques()->delete();
            $produto->delete();
        DB::commit();

            return response()->json(['status' => 'ok', 'message' => 'Produto deletado com sucesso.']);

        } elseif (Auth::user()->role == 'franqueado') {

            $produto->estoques()->where('id_franquia', Auth::user()->franquia_id)->delete();
        DB::commit();
            return response()->json(['status' => 'error', 'message' => 'Franqueados não podem deletar produtos globais.']);
        }

        DB::rollBack();
        return response()->json(['status' => 'error', 'message' => 'Você não tem permissão para deletar este produto.']);
    }


    public function ExportarProduto(Request $request)
    {
        $produto = Produtos::find($request->produto);
        $caracteristicas = ProdutosCaracteristicas::where('id_produto', $request->produto)->get();
        $valores_periodos = PeriodosValores::where('id_produto', $request->produto)->get();
        $promocoes = Promocoes::where('id_produto', $request->produto)->get();
        return response()->json([
            'produto' => $produto,
            'caracteristicas' => $caracteristicas,
            'valores_periodos' => $valores_periodos,
            'promocoes' => $promocoes
        ]);
    }
    public function ExportarFotos($id = null)
    {
        $produto = Produtos::find($id);
        return view('admin.produtos._fotos', compact('produto'));
    }
    public function deleteDetalhes(Request $request, $id)
    {
        $produtodetalhes = ProdutosCaracteristicas::find($id);

        if (!$produtodetalhes) {
            $produtodetalhes = ProdutosFotos::find($id);
            if (!$produtodetalhes) {
                $produtodetalhes = ProdutosVideos::find($id);
            }
        }
        $produtodetalhes->delete();

        return response()->json(['status' => 'ok']);
    }
    public function buscarCatalogo($nome = null)
    {
        $produto = Produtos::where('nome', $nome)->where('produto_catalogo', 'sim')->first();
        if ($produto) {
            return response()->json(['error' => 'Produto já existe no catálogo'], 422);
        }

        return response()->json(['status' => 'ok']);
    }

    public function searchFranquia($id = null)
    {
        $franquia = Franquias::find($id);

        if ($franquia) {
            $periodos = $franquia->periodos;

            if (!$periodos) {
                $periodos = Auth::user()->franquia->periodos;
            }
        } else {
            $periodos = Auth::user()->franquia->periodos;
        }
        $produto = null;

        return view('admin.produtos._periodos', compact('periodos', 'produto'));
    }
}
