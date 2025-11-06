@extends('layouts.app')
@section('assets')
@endsection
@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">

        <div class="card">
            <div class="card-body">

                <div class="row mb-1 ">
                    <div class="col-8 ps-4 my-2">
                        <h4>Produtos</h4>
                    </div>
                    <div class="col-4 my-2 pe-2 text-end">
                        <a href="{{route('admin.produtos_categorias.index')}}" id="filterFranquia" class="btn btn-secondary">
                            Categoria
                        </a>
                        @if(permissao('produtos')->criar == 'sim')
                        <a href="{{route('admin.produtos.new')}}" class="btn btn-primary" type="button">Cadastrar</a>
                        @endif
                    </div>
                    <hr>
                    <div class="col-12">

                        <form id="filtrar" action="{{ route('admin.produtos.index') }}" method="GET" class="row mb-3">

                            @if(Auth::user()->role == 'master' || Auth::user()->role == 'admin')

                            <div class="col-2 ms-3">
                                <label for="">Franquia</label>
                                <select name="franquia" class="form-select">
                                    <option value="todas">Todas</option>
                                    <option value="trip">Trip</option>
                                    <option value="toy">Toy</option>
                                </select>
                            </div>

                            <div class="col-3">
                                <label for="">Unidade</label>
                                <select name="unidade" disabled id="unidade" class="form-select">
                                    <option value="">Selecione</option>
                                    @foreach($franquias as $key => $val)

                                    <option value="{{$val['id']}}">{{$val['nome_franquia']}}</option>

                                    @endforeach
                                </select>
                            </div>
                            @else
                            <div class="col-2 my-3"></div>
                            @endif

                            <div class="col-3">
                                <label for="">Produto</label>
                                <input type="text" name="termo" class="form-control" placeholder="Digite o nome do produto">
                            </div>
                            <div class="col-1 mt-4">
                                <button type="submit" class="btn btn-primary">Filtrar</button>
                            </div>
                            <div class="col-1 mt-4 ms-md-4">
                                <button type="button" id="btnLimpar" class="btn btn-light">Limpar</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div id="lista-Produtos">
                    @include('admin.produtos._list-produtos')
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
    $("body").on('change', 'select[name="franquia"]', function(e) {

        if ($(this).val() == 'toy') {

            $("select[name='unidade']").closest('div').fadeIn('fast')
            document.getElementById('unidade').removeAttribute('disabled');
            document.getElementById('unidade').setAttribute('required', 'required');

        } else if ($(this).val() == 'trip' || $(this).val() == 'todas') {

            unidade.setAttribute('disabled', 'disabled');
            unidade.removeAttribute('required');
            unidade.value = "";
        }
    })

    $('#filtrar').on('submit', function(e) {

        e.preventDefault();
        var url = $(this).attr('action');
        submitFiltro(url)
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
            data: $("#filtrar").serialize(),

            success: function(response) {

                $('#lista-Produtos').html(response);
            },
            error: function(xhr) {

                console.error(xhr.responseText);
            }
        });
    }

    $('#btnLimpar').on('click', function() {

        $('#filtrar')[0].reset();

        $.ajax({
            url: '{{ route("admin.produtos.index") }}',
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
            text: "Você removerá permanentemente este produto.",
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