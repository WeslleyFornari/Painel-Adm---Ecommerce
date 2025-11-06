@extends('layouts.app')

@section('assets')

<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{{asset('css/estilos.css')}}">
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">

        <div class="card">
            <div class="card-body">

                <div class="row mb-2">
                    <div class="col-8 ps-4 my-2">
                        <h4>Pedidos</h4>
                    </div>
                   
                    <div class="col-4 text-end my-2">
                        <a href="{{route('admin.pedidos.new')}}" target="_blank" class="btn btn-success">Criar</a>
                    </div>
                    <hr>

                    <div class="col-12 my-2 ms-5">
                        <form id="filterForm" action="{{ route('admin.pedidos.index') }}" method="GET" class="row mb-3">
                        
                        @if(Auth::user()->role == 'master' || Auth::user()->role == 'admin')
                            <div class="col-2">
                                <select name="franquia" class="form-select">
                                    <option value="todas">Todas</option>
                                    <option value="trip">Trip</option>
                                    <option value="toy">Toy</option>

                                </select>
                            </div>
                        @else
                            <div class="col-2 my-3"></div>
                        @endif
                            <div class="col-2">
                                <select name="campo" class="form-select">
                                    <option value="numero">Número</option>
                                    <option value="cliente">Cliente</option>
        
                                </select>
                            </div>
                            <div class="col-4">
                                <input type="text" name="termo" class="form-control" placeholder="Digite o termo a buscar">
                            </div>
                            <div class="col-1">
                                <button type="submit" class="btn btn-primary">Filtrar</button>
                            </div>
                            <div class="col-1 ms-md-4">
                                <button type="button" id="btnLimpar" class="btn btn-light">Limpar</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div id="lista-Pedidos">
                    @include('admin.pedidos._list-pedidos')
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPedido" tabindex="-1" aria-labelledby="modalPedidoLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 800px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPedidoLabel">Detalhes Pedido</h5>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body" id="conteudo-pedido">

            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalItens" tabindex="-1" aria-labelledby="modalItensLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 800px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalItensLabel">Selecione os itens Entregues/Devolvidos</h5>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body" id="itens">

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')


<script>
    $('#filterForm').on('submit', function(e) {

        e.preventDefault();
        var url = $(this).attr('action');
        console.log(url)
        submitFiltro()
    });

    $("body").on('click', '.pagination .page-link', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        console.log(url)
        submitFiltro(url)
    });

    function submitFiltro(url) {

        $.ajax({
            url: url,
            method: 'GET',
            data: $("#filterForm").serialize(),

            success: function(response) {

                $('#lista-Pedidos').html(response);
            },
            error: function(xhr) {

                console.error(xhr.responseText);
            }
        });
    }


    $('#btnLimpar').on('click', function() {
        $('#filterForm')[0].reset();

        $.ajax({
            url: '{{ route("admin.pedidos.index") }}',
            type: 'GET',
            success: function(response) {
                $('#lista-Pedidos').html(response);
            },
            error: function(xhr) {
                console.log('Erro ao limpar filtro:', xhr);
            }
        });
    });


    $("body").on('click', '.preview-pedido', function() {

        event.preventDefault();
        var url = $(this).attr('href');
        console.log(url);

        $.ajax({
            url: url,
            type: "GET",
            success: function(response) {

                $("#conteudo-pedido").html(response);
                $("#modalPedido").modal('show')

            },
        });
    });
</script>
<script>
    function initAutocomplete() {
        var input = document.getElementById('bairro');
        var options = {
            types: ['(regions)']
        };
        var autocomplete = new google.maps.places.Autocomplete(input, options);
    }
    google.maps.event.addDomListener(window, 'load', initAutocomplete);

    $("body").on('change', '.status', function(e) {
        e.preventDefault();
        var id = this.getAttribute('data-id');
        var idStatus = $(this).val();

        if (idStatus == '6' || idStatus == '7') {
            if (idStatus == '6') {
                var tipo = "entregue";
            } else if (idStatus == '7') {
                var tipo = "devolvido";
            }
            var url = '{{ route("admin.pedidos.mudarStatusEntregueDevolvido", ["id" => ":id", "tipo" => ":tipo"]) }}';
            url = url.replace(':id', id).replace(':tipo', tipo);
            $.get(url, function(data) {
                $("#itens").html(data);
                $("#modalItens").modal('show')
            });
        } else {
            var url = '{{ route("admin.pedidos.mudarStatus", ["id" => ":id", "idStatus" => ":idStatus"]) }}';
            url = url.replace(':id', id).replace(':idStatus', idStatus);
            $.get(url, function(data) {
                swal({
                    title: "Parabéns",
                    text: "Status alterado com sucesso!.",
                    icon: "success",
                })
            });
        }
    });

    $("body").on("submit", "#formStoreEntregueDevolvido", function(e) {
        e.preventDefault();
        $("span.error").remove();
        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success: function(data) {
                swal({
                    title: "Parabéns",
                    text: "Os Itens Entregues/Devolvidos foram registrados com Sucesso!.",
                    icon: "success",
                }).then(function() {
                    location.reload();
                });
                $("#formStore")[0].reset();
                $(".disabled").remove();
            },
            error: function(err) {
                console.log(err);
                if (err.status == 422) {
                    console.log(err.responseJSON);
                    $('#success_message').fadeIn().html(err.responseJSON.message);
                    $.each(err.responseJSON.errors, function(i, error) {
                        var el = $(document).find('[name="' + i + '"]');
                        el.after($('<span class="error" style="color: red;">' + error[0] + '</span>'));
                    });
                }
            }
        });
    });

    $('body').on('click', '.btn-exclude', function(e) {
        e.preventDefault();

        var url = $(this).attr('href');
        var row = $(this).closest('tr');
        console.log(url)
        swal({
            title: "Você tem certeza?",
            text: "Você removerá permanentemente este pedido.",
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
                            swal("Sucesso!", response.message, "success").then(() => {
                                row.remove();
                            });
                            location.reload();
                        } else {
                            swal("Aviso!", response.message, "warning");
                            location.reload();
                            
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
</script>

@endsection