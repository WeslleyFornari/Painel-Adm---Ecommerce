@extends('layouts.app')

@section ('assets')

<style>
    nav .justify-between {
        display: none;
    }

    nav .hidden .relative a svg {
        width: 2% !important;
    }

    nav .hidden .relative span svg {
        width: 2% !important;
    }

    .hidden .relative span span {
        background: #ed3237 !important;
        color: #fff;
    }

    .hidden .relative span .rounded-l-md {
        background: #fff !important;
        color: #67748E;
    }

    .hidden .relative span .rounded-r-md {
        background: #fff !important;
        color: #67748E;
    }

    .icon i {
        font-size: 15px;
        color: #ed3237 !important;
    }

    @media (max-width: 950px) {
        nav .hidden .relative a svg {
            width: 10% !important;
        }

        nav .hidden .relative span svg {
            width: 10% !important;
        }
    }

    .cadastro_estoque {
        display: none;
    }
</style>
@endsection
@section('content')

<div class="row justify-content-center">
    <div class="col-md-12">

        <div class="card">
            <div class="card-body">
                
                    <div class="row">
                        <div class="col-8 ps-4 my-2">
                            <h4>Estoque</h4>
                        </div>
                        <div class="col-4 my-2 pe-2 text-end">
                        @if(permissao('estoque')->criar == 'sim')
                            <button id="cadastro_estoque" class="btn btn-primary float-end mb-0 me-3 ">
                                Cadastro
                            </button>
                        @endif
                        </div>
                        <hr>
                        <div class="col-12">
                        <form id="filterForm" action="{{route('admin.estoque.index')}}" method="GET" class="row mb-0">
                            @if(Auth::user()->role == 'master' || Auth::user()->role == 'admin')
                            
                            <label class="ms-5" for="">Franquia</label>
                            <div class="col-2 text-end ms-5">
                                <select name="franquia" class="form-select">
                                    <option value="todas">Todas</option>
                                    <option value="trip">Trip</option>
                                    <option value="toy">Toy</option>

                                </select>
                            </div>
                            @else
                                <div class="col-2"></div>
                            @endif
                            
                            <div class="col-4">
                                <input type="text" name="termo" class="form-control" placeholder="Digite o nome do produto">
                            </div>
                            <div class="col-1">
                                <button type="submit" class="btn btn-success">Filtrar</button>
                            </div>
                            <div class="col-1 ms-md-4">
                                <button type="button" id="btnLimpar" class="btn btn-light">Limpar</button>
                            </div>
                            </form>
                        </div>
                    </div>
            </div>

            <div class="card-body shadow mx-3 pb-2 cadastro_estoque">
                <form action="{{route('admin.estoque.store')}}" id="formStore" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class=" row">
                        <input type="hidden" name="id">
                        @if (Auth::user()->role != 'franqueado')
                        <div class="form-group col-sm-3">
                            <span class="titulo"> Franquias: </span>
                            <select class="form-select" name="id_franqueado" id="selectFranquia">
                                <option value="">Selecione</option>
                                @foreach($selecionarFranquia as $franquias)
                                <option value="{{$franquias->id}}" data-tipo="{{$franquias->tipo_franqueado}}">{{ $franquias->nome_franquia}}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div class="form-group col-sm-3">
                            <span class="titulo"> Produtos: </span>
                            <div class="" id="listProduto">
                                @include('admin.estoque._listProduto')
                            </div>
                        </div>
                        <div class="form-group col-sm-3">
                            <span class="titulo"> Prefixo Cód. (ex: CAD) *</span>
                            <input type="text" name="codigo" value="" class="form-control text-uppercase" required>
                        </div>
                        <div class="form-group col-sm-3">
                            <span class="titulo"> Qtd.: *</span>
                            <input type="text" name="qtd" value="" class="form-control" required>
                        </div>

                        @if (Auth::user()->role == 'franqueado' && Auth::user()->franquia->tipo_franqueado == 'trip')
                        <input type="hidden" name="tipo_locacao" id="tipo_locacao">
                        @else
                            <div class="form-group col-sm-3">
                                <span class="titulo"> Tipo Locação: </span>
                                <select class="form-select" name="tipo_locacao" id="tipo_locacao">
                                    <option value="aluguel" selected >Aluguel</option>
                                    <option value="aluguel_venda" >Aluguel/Venda</option>
                                    <option value="venda">Venda</option>
                                    <option value="variavel">Variavel</option>
                                </select>
                            </div>
                        @endif

                        <div class="form-group col-sm-3">
                            <span class="titulo"> Data Compra: </span>
                            <input type="date" name="data_compra" value="" class="form-control">
                        </div>
                        <div class="form-group col-sm-3">
                            <span class="titulo"> Valor Compra: </span>
                            <input type="text" name="valor_compra" value="" class="form-control moneyMask">
                        </div>
                    </div>
                    <div class="row justify-content-end">

                        <div class="col-sm-3 col-12  text-end">
                            <button type="submit" class="btn btn-primary mt-2">Salvar</button>
                            <button type="button" class="btn btn-secondary mt-2" id="close">Cancelar</button>
                        </div>
                    </div>
                </form>
            </div>

            <div id="lista-Estoques">
                @include('admin.estoque._list')
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
    <script>
        $("body").on('change', '.status', function(e) {
            e.preventDefault();
            var id = this.getAttribute('data-id');
            var status = $(this).val();

            var url = '{{ route("admin.estoque.mudarStatus", ["id" => ":id", "status" => ":status"]) }}';
            url = url.replace(':id', id).replace(':status', status);
            $.get(url, function(data) {
                swal({
                    title: "Parabéns",
                    text: "Status alterado com sucesso!.",
                    icon: "success",
                })
            });
        });

        $('body').on('click', '.btn-exclude', function(e) {
        e.preventDefault();
        var url = $(this).attr('href'); 
        var row = $(this).closest('.row'); 

        swal({
            title: "Você tem certeza?",
            text: "Esta ação removerá ou tornará o estoque indisponível.",
            icon: "warning",
            dangerMode: true,
            buttons: {
                cancel: {
                    text: "Cancelar",
                    visible: true,
                    closeModal: true,
                },
                confirm: {
                    text: "OK",
                    visible: true,
                    closeModal: false 
                }
            }
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: url,
                    type: 'DELETE', 
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
                    },
                    success: function(response) {
                        if (response.status === 'ok') {
                            swal("Sucesso!", "Estoque deletado com sucesso.", "success").then(() => {
                                row.remove(); 
                            });
                        } else if (response.status === 'error') {
                            swal("Aviso!", response.message, "warning");
                            row.find('.col-3, .col-4, .col-2').addClass('text-muted'); 
                        }
                    },
                    error: function(xhr) {
                        var errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Erro ao processar a requisição.';
                        swal("Erro!", errorMsg, "error");
                    }
                });
            }
        });
    });

    $('#filterForm').on('submit', function(e) {

        e.preventDefault();
        var url = $(this).attr('action');
        submitFiltro()
    });

    $("body").on('click', '.pagination .page-link', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        submitFiltro(url)
    })

    function submitFiltro(url) {

    $.ajax({
        url: url,
        method: 'GET',
        data: $("#filterForm").serialize(),

        success: function(response) {

            $('#lista-Estoques').html(response);
        },
        error: function(xhr) {

            console.error(xhr.responseText);
        }
    });
    }

    $('#btnLimpar').on('click', function() {

        $('#filterForm')[0].reset();

        $.ajax({
            url: '{{ route("admin.estoque.index") }}',
            type: 'GET',
            success: function(response) {
                $('#lista-Estoques').html(response);
            },
            error: function(xhr) {
                console.log('Erro ao limpar filtro:', xhr);
            }
        });
    });




    $("#formStore").submit(function(e) {
        e.preventDefault();
        $("span.error").remove()
        $("#tipo_locacao").prop("disabled", false);

        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success: function(data) {
                if (data === "editado") {
                    swal({
                        title: "Parabéns",
                        text: "Editado com sucesso!",
                        icon: "success",
                    }).then(function() {
                        location.reload();
                    });
                } else {
                    swal({
                        title: "Parabéns",
                        text: "Cadastro realizado com sucesso!",
                        icon: "success",
                    }).then(function() {
                        location.reload();
                    });
                }
                $("#formStore")[0].reset();
                $(".disabled").remove();
            },
            error: function(err) {
                console.log(err);

                if (err.status == 422) { // when status code is 422, it's a validation issue
                    var result = err.responseJSON;
                    swal({
                        title: "Atenção",
                        text: result.msg,
                        icon: "error",
                    })
                }
            }
        })
    })

    $("body").on('click', '.edit', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $.get(url, function(data) {
            console.log(data)
            if ($(".cadastro_estoque").css("display") === "none") {
                $(".cadastro_estoque").show();
            }
            $("#formStore").attr('action', "{{route('admin.estoque.update')}}");
            $("#formStore").append('<input type="hidden" name="_method" value="PUT">');
            $("#formStore input[name='id']").val(data.id);
            $("#formStore input[name='codigo']").val(data.codigo);
            $("#formStore input[name='qtd']").attr('disabled', true);
            $("#formStore select[name='id_franqueado']").val(data.id_franqueado);
            $("#formStore input[name='valor_compra']").val(data.valor_compra);
            $("#formStore input[name='data_compra']").val(data.data_compra);
            $("#formStore select[name='tipo_locacao']").val(data.tipo_locacao);
            $("#formStore select[name='id_produto']").val(data.id_produto);
            $("#formStore select[name='id_produto']").attr('disabled', true);
            $("#formStore").addClass('show');

        })
    })

    $(document).ready(function() {
        $("#cadastro_estoque").click(function() {
            if ($(".cadastro_estoque").css("display") === "none") {

                $(".cadastro_estoque").show();
            }
            $("#formStore").attr('action', "{{route('admin.estoque.store')}}");
            $("#formStore").append('<input type="hidden" name="_method" value="POST">');
            $("#formStore input[name='qtd']").attr('disabled', false);
            $("#formStore select[name='id_produto']").attr('disabled', false);
            $("#formStore")[0].reset();
        });
    });

    $(document).ready(function() {
        $("#close").click(function() {
            $(".cadastro_estoque").hide();
            $("#formStore").find('input[name="_method"]').remove();

        });
    });

    document.getElementById('selectFranquia').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const tipoFranqueado = selectedOption.getAttribute('data-tipo');
    const tipoLocacaoSelect = document.getElementById('tipo_locacao');

    if (tipoFranqueado === 'trip') {
        tipoLocacaoSelect.value = 'aluguel';
        tipoLocacaoSelect.disabled = true;
    } else {
        tipoLocacaoSelect.disabled = false;
    }
});

$("body").on('change', '#selectFranquia', function (e) {
    var id = $('#selectFranquia').val();
    console.log(id);

    var url = '{{ route("admin.estoque.searchProduto", ["id" => ":id"]) }}';
    url = url.replace(':id', id);
    $.get(url, function (data) {
        $('#listProduto').html(data)
    });
});


</script>
@endsection