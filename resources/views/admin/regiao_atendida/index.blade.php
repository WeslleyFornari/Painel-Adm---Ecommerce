@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-7 ps-4 my-2">
                        <h4>Região Atendida</h4>
                    </div>
                    @if(permissao('regiao_atendida')->criar == 'sim')
                    <div class="col-5 my-2 pe-4 text-end">
                        <a href="{{route('admin.regiao_atendida.cadastro') }}" class="btn btn-primary">Cadastrar </a>
                    </div>
                    @endif
                </div>

                <!-- Filtro de Busca -->

                <form id="filterForm" class="float-center">
                    <div class="row">
                        <!-- Filtros por campo e valor -->
                        <div class="col-12 col-md-12">

                            <div class="row" style="margin-left:10px;">
                                <!-- <input type="hidden" class="localizacao" id="localizacao" name="localizacao" value="bairro"> -->
                                @if(Auth::user()->role != 'franqueado')
                                <div class="col-2">
                                    <div class="mb-3">
                                        <label for="Franquia" class="form-label">Franquia</label>
                                        <select name="tipo" id="tipo" class="form-select">
                                            <option value="todas">Todas</option>
                                            <option value="trip">TRIP</option>
                                            <option value="toy">TOY</option>
                                        </select>
                                    </div>
                                </div>
                                @endif
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="Bairro" class="form-label">Bairro</label>
                                        <input type="text" class="form-control" id="bairro" name="bairro" placeholder="digite o bairro">
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="Cidade" class="form-label">Cidade</label>
                                        <input type="text" class="form-control" id="cidade" name="cidade" placeholder="digite a cidade">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <label for="Status" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-select select2">
                                        <option value="">Selecione</option>
                                        <option value="ativo">Ativo</option>
                                        <option value="inativo">Inativo</option>
                                    </select>
                                </div>


                                <!-- Botões -->
                                <div class="col-3" style="margin-top:26px;">
                                    <button type="submit" class="btn btn-primary">filtrar</button>
                                    <button type="button" class="btn btn-danger" id="clearFilterBtn">Limpar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <div class="col-md-12 mt-2" id="lista-Produtos">
        @include('admin.regiao_atendida._list')
    </div>
</div>
@endsection

@section('scripts')
<script>
    function carregarLista(url = '{{ route("admin.regiao_atendida.filter") }}') {

        var formData = $('#filterForm').serialize();

        $.ajax({
            url: url,
            type: 'GET',
            data: formData,

            success: function(response) {
                $('#lista-Produtos').html(response.html);
                $('#pagination-links').html(response.pagination);

            },
            error: function() {
                alert('Erro ao filtrar resultados');
            }
        });
    }

    $('#filterForm').submit(function(e) {
        e.preventDefault();
        var url = $(this).attr('action');
        carregarLista(url);
    });

    $("body").on('click', '.pagination .page-link', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        carregarLista(url)
    });



    $('#clearFilterBtn').click(function() {
        $('#filterForm')[0].reset();
        $('.select2').val(null).trigger('change');
        carregarLista();
    });


    $("body").on('change', '.status-categoria', function(e) {
        e.preventDefault();
        var id = this.getAttribute('data-id');
        console.log("O estado do checkbox mudou! " + id);

        var url = '{{ route("admin.regiao_atendida.mudarStatus", ["id" => ":id"]) }}';
        url = url.replace(':id', id);
        $.get(url, function(data) {
            swal({
                title: "Parabéns",
                text: "Status alterado com sucesso!.",
                icon: "success",
            })
        });
    });
</script>
@endsection