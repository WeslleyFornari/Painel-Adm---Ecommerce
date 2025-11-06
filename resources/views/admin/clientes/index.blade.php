@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">

        <div class="card">
            <div class="card-body">

                <div class="row">

                    <div class="col-9 ps-4 my-2">
                        <h4>Clientes</h4>
                    </div>

                    @if(permissao('clientes')->criar == 'sim')

                    <div class="col-3 my-2 pe-4 text-end">
                        <a href="{{route('admin.clientes.new')}}" class="btn btn-success">Adicionar</a>
                    </div>
                    @endif
                </div>
                <hr>
                <form id="filterForm" action="{{ route('admin.clientes.index') }}" method="GET" class="row mb-3">
                    <div class="row mt-2 mb-2" style="margin-left:120px;">
                        <div class="col-2">
                            <select name="campo" id="select" class="form-select">
                                <option value="cliente">Cliente</option>
                                <option value="cpf">CPF</option>

                            </select>
                        </div>
                        <div class="col-4">
                            <input type="text" id="buscar" name="termo" class="form-control" placeholder="Digite o termo a buscar">
                        </div>
                        <div class="col-1">
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                        </div>
                        <div class="col-1 ms-md-4">
                            <button type="button" id="btnLimpar" class="btn btn-light mx-2">Limpar</button>
                        </div>
                    </div>
                </form>

                <div id="lista-clientes" class="lista-clientes">
                    @include('admin.clientes._list-clientes')
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal-->
<div class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" role="dialog" aria-hidden="true" id="Modalcliente">

    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Detalhes do Cliente</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close"><i class="fa-regular fa-circle-xmark"></i></button>
            </div>

            <div class="modal-body" id="conteudo-cliente">

            </div>

        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {

    procurar();

        $("#select").change(function() {
            procurar();
        });

        function procurar() {

            var input = $("#buscar");
            var selectedValue = $("#select").val(); 
            input.val("");

            if (selectedValue === "cpf") {
                input.addClass("cpfMask"); 
                input.mask("000.000.000-00", { reverse: true });
            } else {
                input.removeClass("cpfMask");
                input.unmask(); 
            }
        }
    });
</script>

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

                $(".lista-clientes").html(response);

            },
            error: function(xhr) {

                console.error(xhr.responseText);
            }
        });
    }

    $('#btnLimpar').on('click', function() {

        $('#filterForm')[0].reset();
        submitFiltro() 
    });


    // PREVIEW
    $("body").on('click', '.preview-cliente', function() {

        event.preventDefault();
        var url = $(this).attr('href');
        console.log(url);

        $.ajax({
            url: url,
            type: "GET",

            success: function(response) {

                $("#conteudo-cliente").html(response);
                $("#Modalcliente").modal('show')

            },
        });
    });

    $("body").on('change', '.status-categoria', function(e) {
        e.preventDefault();
        var id = this.getAttribute('data-id');
        console.log("O estado do checkbox mudou! " + id);

        var url = '{{ route("admin.clientes.mudarStatus", ["id" => ":id"]) }}';
        url = url.replace(':id', id);
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
        var row = $(this).closest('tr');
        console.log(url)
        swal({
            title: "Você tem certeza?",
            text: "Você removerá permanentemente este cliente.",
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