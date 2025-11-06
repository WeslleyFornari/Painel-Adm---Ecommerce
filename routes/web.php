<?php

use App\Http\Controller\Admin\RelatoriosController;
use App\Http\Controllers\Admin\DuvidasFrequentesController;
use App\Http\Controllers\Admin\DepoimentosController;
use App\Http\Controllers\Admin\ConfiguracoesController;
use App\Http\Controllers\Admin\BannersController;
use App\Http\Controllers\Admin\AgendaController;
use App\Http\Controllers\Admin\CuponsController;
use App\Http\Controllers\Admin\DadosClientesController;
use App\Http\Controllers\Admin\FranquiasController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\EstoqueController;
use App\Http\Controllers\Admin\PermissoesController;
use App\Http\Controllers\Admin\ProdutosController;
use App\Http\Controllers\Admin\ProdutosCategoriasController;
use App\Http\Controllers\Admin\PedidosController;
use App\Http\Controllers\Admin\BaseConhecimentoController;
use App\Http\Controllers\Admin\BaseConhecimentoCategoriasController;
use App\Http\Controllers\Admin\FormularioFranquiaController;;
use App\Http\Controllers\Admin\RegiaoAtendidaController;
use App\Http\Controllers\Admin\CEPBloqueadosController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\ClientesController;
use App\Http\Controllers\Admin\PDFController;
use App\Http\Controllers\Admin\RelatoriosController as AdminRelatoriosController;
use App\Http\Controllers\Admin\CalendarioController as AdminCalendarioController;
use App\Http\Controllers\Admin\MarcaController;
use App\Http\Controllers\PerguntasFrequentesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('auth.login');
});

Route::get('/calendario-dados', [HomeController::class, 'getCalendario']);

Route::get('/new_calender', [HomeController::class, 'new_calender']);



Auth::routes();

Route::name('admin.')->prefix('/')->middleware(['auth'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/entregas', [HomeController::class, 'getEntregas'])->name('getEntregas');
    Route::get('/devolucoes', [HomeController::class, 'getDevolucoes'])->name('getDevolucoes');
    Route::post('/filtrar/{tipo}', [HomeController::class, 'filtrar_entregas'])->name('filtrar_entregas');
    Route::get('/limpar', [HomeController::class, 'limpar_filtro'])->name('limpar_filtro');

    Route::name('dashboard.')->prefix('dashboard')->controller(HomeController::class)->group(function () {        Route::get('/index', 'index')->name('index');
        Route::get('/graficosQtdPedidos/{franquia?}', 'graficosQtdPedidos')->name('graficosQtdPedidos');
        Route::get('/index', 'index')->name('index');
        Route::get('/graficosPedidoSemana/{franquia?}', 'graficosPedidoSemana')->name('graficosPedidoSemana');
        Route::get('/graficosProdutosMaisAlugados/{franquia?}', 'graficosProdutosMaisAlugados')->name('graficosProdutosMaisAlugados');
        Route::get('/graficosValorPedidosTodos', 'graficosValorPedidosTodos')->name('graficosValorPedidosTodos');
        Route::get('/graficosPedidoSemanaTodos', 'graficosPedidoSemanaTodos')->name('graficosPedidoSemanaTodos');
        Route::get('/graficosFranquiaPorDia/{data?}', 'graficosFranquiaPorDia')->name('graficosFranquiaPorDia');
    });

    Route::name('formulario_contato.')->prefix('formulario_')->controller(FormularioFranquiaController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::get('/delete/{id}', 'delete')->name('delete');
        Route::get('/preview/{id}', 'preview')->name('preview');
    });

    Route::name('franquias.')->prefix('franquias')->controller(FranquiasController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/new', 'new')->name('new');
        Route::post('/store', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
        Route::get('/preview/{id}', 'preview')->name('preview');
        // Route::match(['get', 'post'], '/empresas/status', 'status')->name('status');
        Route::get('/mudarStatus/{id?}', 'mudarStatus')->name('mudarStatus');
    });
    Route::name('permissoes.')->prefix('permissoes')->controller(PermissoesController::class)->group(function () {
      
        Route::get('/index', 'index')->name('index');
        Route::get('/permissoes/{id}', 'permissoes')->name('permissoes');
        Route::post('/store/{id}', 'store')->name('store');
    });
    Route::name('usuarios.')->prefix('usuarios')->controller(UsuarioController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/new', 'new')->name('new');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id?}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
        Route::get('/preview/{id}', 'preview')->name('preview');
        // Route::match(['get', 'post'], '/usuarios/status', 'status')->name('status');
        Route::get('/mudarStatus/{id?}', 'mudarStatus')->name('mudarStatus');
        Route::match(['get', 'post'], '/procurar', 'procurar')->name('procurar');
    });
    Route::name('clientes.')->prefix('clientes')->controller(ClientesController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/new', 'new')->name('new');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id_user?}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::post('/enderecos/{idUsuario?}', 'enderecos')->name('enderecos');
        Route::delete('/delete/{id}', 'delete')->name('delete');
        Route::get('/deleteEndereco/{id?}', 'deleteEndereco')->name('deleteEndereco');
        Route::get('/preview/{id}', 'preview')->name('preview');
        // Route::match(['get', 'post'], '/usuarios/status', 'status')->name('status');
        Route::get('/mudarStatus/{id?}', 'mudarStatus')->name('mudarStatus');
        Route::match(['get', 'post'], '/procurar', 'procurar')->name('procurar');
    });
    Route::name('dados_clientes.')->prefix('dados_clientes')->controller(DadosClientesController::class)->group(function () {
        Route::get('/edit/{id_user}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
        Route::match(['get', 'post'], '/usuarios/status', 'status')->name('status');
    });
    Route::name('produtos.')->prefix('produtos')->controller(ProdutosController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/new', 'new')->name('new');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::delete('/delete/{id}', 'delete')->name('delete');
        Route::get('/deleteDetalhes/{id}', 'deleteDetalhes')->name('deleteDetalhes');
        Route::get('/mudarStatus/{id?}', 'mudarStatus')->name('mudarStatus');
        Route::get('/mudarStatusDetalhes/{id?}', 'mudarStatusDetalhes')->name('mudarStatusDetalhes');
        Route::post('/ExportarProduto', 'ExportarProduto')->name('ExportarProduto');
        Route::get('/searchFranquia/{id?}', 'searchFranquia')->name('searchFranquia');
        Route::get('/ExportarFotos/{id?}', 'ExportarFotos')->name('ExportarFotos');
        Route::get('/catalogo/index', 'catalogo')->name('catalogo.index');
        Route::get('/catalogo/new', 'newCatalogo')->name('catalogo.new');
        Route::get('/catalogo/buscar/{nome?}', 'buscarCatalogo')->name('catalogo.buscar');
    });
    Route::name('agenda.')->prefix('agenda')->controller(AgendaController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/bloqueios/{id}', 'bloqueios')->name('bloqueios');
        Route::post('/store', 'store')->name('store');
    });
    Route::name('produtos_categorias.')->prefix('produtos_categorias')->controller(ProdutosCategoriasController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/new', 'new')->name('new');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
        Route::get('/mudarStatus/{id?}', 'mudarStatus')->name('mudarStatus');
    });
    Route::name('base_conhecimento.')->prefix('base_conhecimento')->controller(BaseConhecimentoController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
        Route::get('/mudarStatus/{id?}', 'mudarStatus')->name('mudarStatus');
    });
    Route::name('base_conhecimento_categoria.')->prefix('base_conhecimento_categoria')->controller(BaseConhecimentoCategoriasController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
        Route::get('/mudarStatus/{id?}', 'mudarStatus')->name('mudarStatus');
        Route::post('/filtrar', 'filtrar')->name('filtrar');
    });
    Route::name('estoque.')->prefix('estoque')->controller(EstoqueController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update', 'update')->name('update');
        Route::delete('/delete/{id}', 'delete')->name('delete');
        // Route::get('/mudarStatus/{id?}', 'mudarStatus')->name('mudarStatus');
        Route::get('/mudarStatus/{id?}/{status?}', 'mudarStatus')->name('mudarStatus');
        Route::post('/searchProduto', 'searchProduto')->name('searchProduto');
    });
    // Pedidos
    Route::name('pedidos.')->prefix('pedidos')->controller(PedidosController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/preview/{id}', 'preview')->name('preview');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::get('/new', 'new')->name('new');
        Route::POST('/descontos', 'descontos')->name('descontos');
        Route::post('/pesquisarFranquia', 'pesquisarFranquia')->name('pesquisarFranquia');
        Route::post('/produtosDisponivel', 'produtosDisponivel')->name('produtosDisponivel');
        Route::get('/valorPeriodo/{id?}/{id_produto?}/{modalidade?}', 'valorPeriodo')->name('valorPeriodo');
        Route::post('/periodoFranquias', 'periodoFranquias')->name('periodoFranquias');
        Route::post('/store', 'store')->name('store');
        Route::post('/EntregueDevolvido/{id}', 'EntregueDevolvido')->name('EntregueDevolvido');
        Route::post('/endereco/{id?}', 'endereco')->name('endereco');
        Route::post('/qtdMaxProduto', 'qtdMaxProduto')->name('qtdMaxProduto');
        Route::get('/mudarStatus/{id?}/{idStatus?}', 'mudarStatus')->name('mudarStatus');
        Route::get('/mudarStatusEntregueDevolvido/{id?}/{tipo?}', 'mudarStatusEntregueDevolvido')->name('mudarStatusEntregueDevolvido');
        Route::get('/mostrar', 'mostrar')->name('mostrar');
        Route::get('/mudarItens/{id?}/{idItem?}', 'mudarItens')->name('mudarItens');
        Route::get('/mudarUnidade/{id?}/{idFranquia?}', 'mudarUnidade')->name('mudarUnidade');
        Route::get('/busca_cliente', 'buscaCpf')->name('buscaCpf');
        Route::get('/recarrega_cliente', 'recarregarClientes')->name('recarregarClientes');

        Route::delete('/delete/{id}', 'delete')->name('delete');

    });
    // Relatorios
    Route::name('relatorios.')->prefix('relatorios')->controller(AdminRelatoriosController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/pedidosSemPagamento', 'pedidosSemPagamento')->name('pedidosSemPagamento');
        Route::get('/pedidosCliente', 'pedidosCliente')->name('pedidosCliente');
        Route::get('/pedidosFiltro', 'pedidosFiltro')->name('pedidosFiltro');
        Route::get('/mostrar', 'mostrar')->name('mostrar');
        Route::get('/mudarItens/{id?}/{idItem?}', 'mudarItens')->name('mudarItens');
        Route::match(['get', 'post'], '/data/{id?}/{idProduto?}', 'data')->name('data');
        Route::post('/save', 'save')->name('save');
        // Route::get('/calendario', 'calendario')->name('calendario');
        Route::get('/clientesAll', 'clientesAll')->name('clientesAll');
        //  Route::get('/porCliente', 'porCliente')->name('porCliente');
        Route::get('/itemLogistica', 'itemLogistica')->name('itemLogistica');
        Route::get('/logistica', 'logistica')->name('logistica');
        Route::get('/financeiro', 'financeiro')->name('financeiro');
        Route::get('/pagamentos', 'pagamentos')->name('pagamentos');
        Route::get('/clientes', 'clientes')->name('clientes');
        Route::get('/ClientesMaisPedidos', 'ClientesMaisPedidos')->name('ClientesMaisPedidos');
        Route::get('/ClientesMaisGastam', 'ClientesMaisGastam')->name('ClientesMaisGastam');
        Route::get('/curvaABC', 'curvaABC')->name('curvaABC');
        Route::get('/itensEstoque', 'itensEstoque')->name('itensEstoque');
    });

    Route::name('calendarios.')->prefix('calendarios')->controller(AdminCalendarioController::class)->group(function () {
        Route::match(['get', 'post'], '/data/{id?}/{idProduto?}', 'data')->name('data');
        Route::get('/index', 'index')->name('index');
        Route::get('/produtos/{id?}', 'produtos')->name('produtos');
        Route::get('/selectProduto/{id?}', 'selectProduto')->name('selectProduto');
        Route::get('/estoques/{id?}/{id_produto?}', 'estoques')->name('estoques');
        Route::get('/datas/{id?}/{id_produto?}', 'datas')->name('datas');
        Route::post('/store', 'store')->name('store');

    });

    Route::name('pdf.')->prefix('pdf')->controller(PDFController::class)->group(function () {
        Route::get('/pdfEntrega/{id}', 'pdfEntrega')->name('pdfEntrega');
        Route::get('/pdfDevolucao/{id}', 'pdfDevolucao')->name('pdfDevolucao');
        Route::get('/pdfEntregaDevolucao/{id}', 'pdfEntregaDevolucao')->name('pdfEntregaDevolucao');
    });
    Route::name('regiao_atendida.')->prefix('regiao_atendida')->controller(RegiaoAtendidaController::class)->group(function () {
        Route::get('/index', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
        Route::get('/cadastro', 'cadastro')->name('cadastro');
        Route::get('/mudarStatus/{id?}', 'mudarStatus')->name('mudarStatus');
        Route::get('admin/regiao_atendida/filter', 'filter')->name('filter');

    });
    Route::name('cep_bloqueados.')->prefix('cep_bloqueados')->controller(CEPBloqueadosController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/cadastro', 'cadastro')->name('cadastro');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
        Route::get('/mudarStatus/{id?}', 'mudarStatus')->name('mudarStatus');
    });
    Route::name('media.')->prefix('media')->controller(MediaController::class)->group(function () {
        Route::get('/popUp/{folder?}', 'popUp')->name('popUp');
        Route::post('/list-folder/{folder?}', 'listFiles')->name('list-files');
        Route::post('/newFolder', 'newFolder')->name('newFolder');
        Route::get('/delFolder', 'delFolder')->name('delFolder');
        Route::get('/getFile/{id?}', 'getFile')->name('getFile');
        Route::get('/{folder??}', 'index')->name('index');
        Route::get('/tokenUrl', 'tokenUrl')->name('tokenUrl');
        Route::post('/upload-media', 'moveFile')->name('upload-media');
        Route::post('/delete-media', 'deleteFile')->name('delete-media');
    });
    Route::name('banners.')->prefix('banners')->controller(BannersController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/new', 'new')->name('new');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id_user}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
        // Route::match(['get', 'post'], '/banners/status', 'status')->name('status');
        Route::get('/mudarStatus/{id?}', 'mudarStatus')->name('mudarStatus');
        Route::match(['get', 'post'], '/procurar', 'procurar')->name('procurar');
    });
    Route::name('cupons.')->prefix('cupons')->controller(CuponsController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/lista-cupom', 'listaCupom')->name('listaCupom');
        Route::get('/new', 'new')->name('new');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
        Route::get('/preview/{id}', 'preview')->name('preview');
        // Route::match(['get', 'post'], '/cupons/status', 'status')->name('status');
        Route::get('/mudarStatus/{id?}', 'mudarStatus')->name('mudarStatus');
        Route::match(['get', 'post'], '/procurar', 'procurar')->name('procurar');
    });
    Route::name('depoimentos.')->prefix('depoimentos')->controller(DepoimentosController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/new', 'new')->name('new');
        Route::post('/store', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
        Route::get('/preview/{id}', 'preview')->name('preview');
        Route::match(['get', 'post'], '/depoimentos/status', 'status')->name('status');
        Route::match(['get', 'post'], '/procurar', 'procurar')->name('procurar');
    });
    Route::name('duvidas.')->prefix('duvidas')->controller(DuvidasFrequentesController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/new', 'new')->name('new');
        Route::post('/store', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
        Route::get('/preview/{id}', 'preview')->name('preview');
        Route::match(['get', 'post'], '/duvidas/status', 'status')->name('status');
        Route::match(['get', 'post'], '/procurar', 'procurar')->name('procurar');
    });
    Route::name('configuracoes.')->prefix('configuracoes')->controller(ConfiguracoesController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/new', 'new')->name('new');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
        Route::get('/preview/{id}', 'preview')->name('preview');
        Route::match(['get', 'post'], '/cupons/status', 'status')->name('status');
        Route::match(['get', 'post'], '/procurar', 'procurar')->name('procurar');
    });
    Route::name('perguntas_frequentes.')->prefix('perguntas_frequentes')->controller(PerguntasFrequentesController::class)->group(function () {
        Route::get('/index', 'index')->name('index');
        Route::post('/store/{id}', 'store')->name('store');
        Route::get('/responder/{id}', 'responder')->name('responder');
        Route::put('/update', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
        Route::get('/mudarStatus/{id?}', 'mudarStatus')->name('mudarStatus');
    });

    Route::name('marcas.')->prefix('marcas')->controller(MarcaController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/getmarcas', 'getmarcas')->name('getmarcas');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::get('/delete/{id}', 'delete')->name('delete');
            Route::post('/update/{id}', 'update')->name('update');
            Route::get('/mudarStatus/{id?}', 'mudarStatus')->name('mudarStatus');
        }
    );
});
