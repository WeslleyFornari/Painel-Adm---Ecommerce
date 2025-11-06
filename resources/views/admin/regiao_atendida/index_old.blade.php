@extends('layouts.app')

@section ('assets')

<style>
    nav .justify-between{
        display: none;
    }

    nav .hidden .relative a svg{
        width: 2% !important;
    }
    nav .hidden .relative span svg{
        width: 2% !important;
    }

    .hidden .relative span span{
        background: #ed3237 !important;
        color: #fff;
    }

    .hidden .relative span .rounded-l-md{
        background: #fff !important;
        color: #67748E;
    }

    .hidden .relative span .rounded-r-md{
        background: #fff !important;
        color: #67748E;
    }

    .icon i{
        font-size: 15px;
        color: #ed3237 !important;
    }

@media (max-width: 950px) {
    nav .hidden .relative a svg{
        width: 10% !important;
    }
    nav .hidden .relative span svg{
        width: 10% !important;
    }
}

</style>
@endsection
@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
   
        <div class="card">
            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-7 ps-4 my-2">
                        <h4>Região Atendida</h4>          
                    </div>
                    <div class="col-5 my-2 pe-4 text-end">
                        <a href="{{route('admin.regiao_atendida.cadastro')}}" class="btn btn-primary">Cadastrar </a>
                    </div>
                    <hr>
                    <div class="col-12">
                        <div class="row mb-2 ms-5">
                            <div class="col-md-2">
                                <label for="">Localização</label>
                                <input type="text" class="form-control" id="filter-localizacao" placeholder="Localização">
                            </div>
                            @if(Auth::user()->role != 'franqueado')
                            <div class="col-md-4">
                                <label for="">Franquia</label>
                                <select class="form-control" id="filter-franquia-id" name="filter-franquia-id">
                                    <option value="">Selecionar Franquia</option>
                                    @foreach($franquias as $franquia)
                                        <option value="{{ $franquia->id }}">{{ $franquia->nome_franquia }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <div class="col-md-2">
                                <label for="">Status</label>
                                <select class="form-control" id="filter-status">
                                    <option value="">Selecione</option>
                                    <option value="ativo">Ativo</option>
                                    <option value="inativo">Inativo</option>
                                </select>
                            </div>
                            <div class="col-md-1 pt-4 mx-3">
                                <button class="btn btn-primary" id="btn-filtrar">Filtrar</button>
                              
                            </div>
                            <div class="col-md-1 pt-4 mx-2">
                               
                                <button class="btn btn-light" id="btn-limpar">Limpar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="lista-Produtos">
                    @include('admin.regiao_atendida._list')
                </div> 
           </div>
        </div>
        

    </div>
</div>

@endsection

@section('scripts')

<script>

$("body").on('change', '.status-categoria', function (e) {
    e.preventDefault();
    var id = this.getAttribute('data-id');
    console.log("O estado do checkbox mudou! " + id);

    var url = '{{ route("admin.regiao_atendida.mudarStatus", ["id" => ":id"]) }}';
    url = url.replace(':id', id);
    $.get(url, function (data) {
        swal({
            title: "Parabéns",
            text: "Status alterado com sucesso!.",
            icon: "success",
        })
    });
});

$('#btn-filtrar').click(function() {
    let localizacao = $('#filter-localizacao').val();
    let franquiaId = $('#filter-franquia-id').val();
    let status = $('#filter-status').val();

    carregarLista(localizacao, franquiaId, status);
});

$('#btn-limpar').click(function() {
    $('#filter-localizacao').val('');
    $('#filter-franquia-id').val('');
    $('#filter-status').val('');

    carregarLista();
});

function carregarLista(localizacao = '', franquiaId = '', status = '', page = 1) {
    $.ajax({
        url: '{{ route("admin.regiao_atendida.filter") }}',
        type: 'GET',
        data: {
            localizacao: localizacao,
            'filter-franquia-id': franquiaId,
            status: status,
            page: page 
        },
        success: function(data) {
            $('#lista-Produtos').html(data);
        },
        error: function() {
            alert('Erro ao filtrar resultados');
        }
    });
}

$("body").on("click", ".pagination a", function(e) {
    e.preventDefault();
    let page = $(this).attr("href").split("page=")[1];
    
    let localizacao = $('#filter-localizacao').val();
    let franquiaId = $('#filter-franquia-id').val();
    let status = $('#filter-status').val();
    
    carregarLista(localizacao, franquiaId, status, page);
});

</script>
@endsection







  