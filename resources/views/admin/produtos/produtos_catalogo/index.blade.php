@extends('layouts.app')
@section('assets')
@endsection
@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">

        <div class="card">
            <div class="card-body">

                    <div class="row mb-1">
                        <div class="col-8 ps-4 my-2">
                            <h4>Produtos Catálogo</h4>
                        </div>
                        <div class="col-4 my-2 pe-2 text-end">
                            <a href="{{route('admin.produtos.catalogo.new')}}" class="btn btn-primary " type="button">Cadastrar</a>
                        </div>
                        <hr>
                        <div class="col-12">

                            <form id="filterFranquia" action="{{ route('admin.produtos.catalogo.index') }}" method="GET" class="row mb-3">
                            
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
                                <div class="col-2 my-3"></div>
                            @endif
                                <div class="col-4">
                                    <input type="text" name="termo" class="form-control" placeholder="Digite o nome do produto">
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

                <div id="lista-Produtos">
                    @include('admin.produtos.produtos_catalogo._list-produtos')
                </div>

                <div class="row mt-3 justify-content-center">
                    <div class="col-sm-12 mx-auto align-center">
                        {{ $produtos->links() }}
                    </div>
                </div>

            </div>
            <div class="card-footer">
                <div class="col">
                    <a class="btn btn-secondary m-0" href="{{route('admin.produtos.index')}}">Voltar</a>
                </div>
            </div>
        </div>

    </div>
 
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>

<script>
    $('#filterFranquia').on('submit', function(e) {

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
            data: $("#filterFranquia").serialize(),

            success: function(response) {

                console.log('resposta ok')
                $('#lista-Produtos').html(response);
            },
            error: function(xhr) {

                console.error(xhr.responseText);
            }
        });
    }

    $('#btnLimpar').on('click', function() {
        $('#filterFranquia')[0].reset();

        $.ajax({
            url: '{{ route("admin.produtos.catalogo.index") }}',
            type: 'GET',
            success: function(response) {
                $('#lista-Produtos').html(response);
            },
            error: function(xhr) {
                console.log('Erro ao limpar filtro:', xhr);
            }
        });
    });


    $("body").on('change', '.status-categoria', function(e) {
        e.preventDefault();
        var id = this.getAttribute('data-id');
        console.log("O estado do checkbox mudou! " + id);

        var url = '{{ route("admin.produtos_categorias.mudarStatus", ["id" => ":id"]) }}';
        url = url.replace(':id', id);
        $.get(url, function(data) {
            swal({
                title: "Parabéns",
                text: "Status alterado com sucesso!.",
                icon: "success",
            })
        });
    });

    function toggleCollapse(id) {
        var content = document.getElementById("collapseContent" + id);
        if (content.style.display === "none") {
            content.style.display = "block"; // Mostrar o conteúdo quando estiver oculto
        } else {
            content.style.display = "none"; // Ocultar o conteúdo quando estiver visível
        }
    }
    $("body").on('change', '.status-categoria', function(e) {
        e.preventDefault();
        var id = this.getAttribute('data-id');
        console.log("O estado do checkbox mudou! " + id);

        var url = '{{ route("admin.produtos.mudarStatus", ["id" => ":id"]) }}';
        url = url.replace(':id', id);
        $.get(url, function(data) {
            swal({
                title: "Parabéns",
                text: "Status alterado com sucesso!.",
                icon: "success",
            })
        });
    });
    $("body").on('change', '.status-categoria-detalhes', function(e) {
        e.preventDefault();
        var id = this.getAttribute('data-id');
        console.log("O estado do checkbox mudou! " + id);

        var url = '{{ route("admin.produtos.mudarStatusDetalhes", ["id" => ":id"]) }}';
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

        swal({
            title: "Você tem certeza?",
            text: "Você removerá permanentemente este item.",
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
                        } else {
                            swal("Aviso!", response.message, "warning");
                            location.reload();na
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