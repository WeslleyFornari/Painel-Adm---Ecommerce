<div class="row">
    @if (Auth::user()->role == 'franqueado')
    <input type="hidden" value="{{Auth::user()->franquia->tipo_franqueado}}" name="tipo" id="tipo">
    @else
    <div class="form-group col-sm-1">
        <span class="titulo"> Tipo:</span>
        <select class="form-select" name="tipo" id="tipo">
            <option value="trip" selected @if($produto?->tipo == 'trip') selected @endif>Trip</option>
            <option value="toy" @if($produto?->tipo == 'toy') selected @endif>Toy</option>
        </select>
    </div>
    @endif
    @if (Auth::user()->role == 'franqueado')
    <input type="hidden" value="{{Auth::user()->franquia->id}}" name="id_franqueado">
    @else
        <div class="form-group col-sm-2" id="card_franquia" style="display:none">
            <span class="titulo"> Franquia: </span>
            <select class="form-select" name="id_franqueado">
                <option value="">Selecione</option>
                @foreach($selecionarFranquia as $franquia)
                    <option value="{{$franquia->id}}" @if($produto?->id_franquia == $franquia->id) selected @endif>{{ $franquia->nome_franquia}}</option>
                @endforeach
            </select>
        </div>
    @endif
    <div class="form-group col-sm-6">
        <span class="titulo"> Nome: *</span>
        <input type="text" name="nome" value="{{$produto?->nome}}" class="form-control">  
    </div>
    <div class="form-group col-sm-3">
        <span class="titulo"> Categoria: *</span>
        <select class="form-select cat_trip" name="categoria_trip">
            <option value="">Selecione</option>
            @foreach($categorias_trip as $cat_trip)
                <option value="{{ $cat_trip->id }}" @if ($cat_trip->id == $produto?->id_categoria) selected @endif>{{ $cat_trip->nome }}</option>
            @endforeach
        </select>
        <select class="form-select cat_toy" name="categoria_toy" style="display: none">
            <option value="">Selecione</option>
            @foreach($categorias_toy as $cat_toy)
                <option value="{{ $cat_toy->id }}" @if ($cat_toy->id == $produto?->id_categoria) selected @endif>{{ $cat_toy->nome }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-sm-6">
        <span class="titulo"> Descriçao: *</span>
        <textarea id="summernote" name="descricao">
            {{$produto?->descricao}}
        </textarea>
        <!-- <input type="text" name="descricao" value="{{$produto?->descricao}}" class="form-control" required>   -->
    </div>
    <div class="form-group col-sm-6">
        <span class="titulo"> Orientações ao cliente: </span>
        <textarea id="summernote_orientacoes" name="orientacoes">
            {{$produto?->orientacoes}}
        </textarea>
    </div>
    <input type="hidden" id="modalidadeInput" value="{{$produto?->modalidade ?? 'alugar'}}">
    <div class="form-group col-sm-4" id="modalidade" @if($produto?->tipo != 'toy')style="display: none" @endif >
        <span class="titulo"> Modalidade:</span>
        <select class="form-select" name="modalidade">
            <option value="alugar" @if($produto?->modalidade == 'alugar') selected @endif>Alugar</option>
            <option value="vender" @if($produto?->modalidade == 'vender') selected @endif>Vender</option>
            <option value="alugar_vender" @if($produto?->modalidade == 'alugar_vender') selected @endif>Alugar/Vender</option>
            <option value="variavel" @if($produto?->modalidade == 'variavel') selected @endif>Variável</option>
        </select>
    </div>
    <div class="form-group col-sm-4">
        <span class="titulo me-5"> Marca: *</span><a href="#" id="abrirMarca">
            <span class="titulo ms-5 text-danger"><b>Cadastrar</b></span>
        </a>
        <select name="marca" id="marcas" class="form-select">
            <option value="">Selecione</option>
            @foreach($marcas as $k => $value)
                <option value="{{$value->id}}"  @if($produto?->marca == $value->id) selected @endif>{{ $value->nome }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-sm-4">
        <span class="titulo"> Idade Recomendada:</span>
        <select name="idade" class="form-select select2-tags">
            @foreach($idades as $ki => $vi)
            <option value="{{$vi}}" @if($produto?->idade == $vi) selected="selected" @endif>{{$vi}}</option>
            @endforeach
           
            </select>
        
    </div>
    <div class="form-group col-sm-4" id="valores_aluguel">
        @include('admin.produtos._periodos')
    </div>
    <div class="form-group col-sm-4" id="valor_div">
        <span class="titulo" id="valor"> Valor diário: *</span>
        <input type="text" name="valor_base_diaria" value="{{$produto?->valor_base_diaria}}" class="form-control moneyMask">  
    </div>
    <div class="form-group col-sm-4">
        <span class="titulo"> Peso Máximo em KG:</span>
        <input type="int" name="peso_maximo" value="{{$produto?->peso_maximo}}" class="form-control">  
    </div>
    @if(Auth::check() && in_array(Auth::user()->role, ['master', 'admin']))
        <div class="col-12">
            <div class="form-check">
                <input 
                    class="form-check-input" 
                    type="checkbox" 
                    value="sim" 
                    id="CheckCatalogo" 
                    name="catalogo" 
                    @checked($catalogo)
                >
                <label class="form-check-label" for="CheckCatalogo">
                    Produto para catálogo
                </label>
            </div>
        </div>
    @endif
    <div class="" id="produto_recomendo">
        <h4>Produto Recomendado</h4>
        <p>Produto que recomenda para alugar junto</p>
        <div class="form-group col-sm-4">
            <select class="form-select" name="id_produto_recomendado">
                <option value="">Selecione</option>
                @foreach($produtos as $prod)
                    <option value="{{ $prod->id }}" @if ($prod->id == $produto?->id_produto_recomendado) selected @endif>{{ $prod->nome }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="text-end">
    <button type="button" class="btn btn-primary caracteristica">Próximo</button>
</div>