@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">

        <div class="collapse" id="collapse-Cupom">
            <div class="card shadow mb-4">
                <div class="card-body " id="cadastro-cupons">
                    <!--      <a href="#" class="tooglegeCollapse float-right" data-target="#collapse-User"><i class="fas fa-times"></i></a> -->
                    @include('admin.cupons.cadastrar')
                </div>
            </div>
        </div>

        <div class="collapse" id="collapse-Cupom2">
            <div class="card shadow mb-4">
                <div class="card-body " id="edit-cupons">
                    <!--      <a href="#" class="tooglegeCollapse float-right" data-target="#collapse-User"><i class="fas fa-times"></i></a> -->
                    @include('admin.cupons.cadastrar')
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form id="filterForm" action="{{ route('admin.pedidos.index') }}" method="GET" class="row mb-1">

                    <div class="row mb-1">
                        <div class="col-8 ps-4 my-2">
                            <h4>Cupons</h4>
                        </div>

                        @if(permissao('cupons')->criar == 'sim')
                        <div class="col-4 my-2 text-end">
                            <a class="btn btn-primary tooglegeCollapse" type="button" data-target="#collapse-Cupom">Cadastrar</a>
                            <!--    <a href="#collapse-Cupom" data-bs-toggle="collapse">teste</a> -->
                        </div>
                       @endif
                    </div>
                    <hr>
                    <div class="row mb-3">
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
                        <div class="col-2 my-2"></div>
                        @endif
                        <div class="col-4">
                            <input type="text" name="termo" class="form-control" placeholder="digite o código do cupom">
                        </div>
                        <div class="col-2 text-end pe-2">
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                        </div>
                        <div class="col-2 text-center">
                            <button type="button" id="btnLimpar" class="btn btn-light ms-2">Limpar</button>
                        </div>
                    </div>
                </form>

                <div id="lista-Cupons">
                    @include('admin.cupons._list-cupons')
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>

    $("#tipo_franqueado").change(function (e) {

        if ($(this).val() === 'trip') {
                $('#id_franquia').prop('disabled', true);
            } else {
                $('#id_franquia').prop('disabled', false);
            }
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
    });

    function submitFiltro(url) {

        $.ajax({
            url: url,
            method: 'GET',
            data: $("#filterForm").serialize(),

            success: function(response) {

                console.log('resposta ok')
                $('#lista-Cupons').html(response);
            },
            error: function(xhr) {

                console.error(xhr.responseText);
            }
        });
    }

    $('#btnLimpar').on('click', function() {

        $('#filterForm')[0].reset();

        $.ajax({
            url: '{{ route("admin.cupons.index") }}',
            type: 'GET',
            success: function(response) {
                $('#lista-Cupons').html(response);
            },
            error: function(xhr) {
                console.log('Erro ao limpar filtro:', xhr);
            }
        });
    });


    function toggleCollapse() {
        if ($('#collapse-Cupom').hasClass('show')) {
            $('#collapse-Cupom').collapse('show');
        } else {
            $("#formStore")[0].reset();
            $('#id_franquia').prop('disabled', false);
            $('#card_franquia').css('display', 'block');
            $("#formStore").attr('action', "{{route('admin.cupons.store')}}");
            $("#formStore").append('<input type="hidden" name="_method" value="POST">');
            $('#collapse-Cupom').collapse('hide');
        }
    }


    $('body').on('click', 'a[data-target="#collapse-Cupom"]', function() {
        toggleCollapse();
        $('.moneyMask').mask("#.##0,00", {
            reverse: true
        });
    });

    $("body").on('change', '.status-categoria', function(e) {
        e.preventDefault();
        var id = this.getAttribute('data-id');
        console.log("O estado do checkbox mudou! " + id);

        var url = '{{ route("admin.cupons.mudarStatus", ["id" => ":id"]) }}';
        url = url.replace(':id', id);
        $.get(url, function(data) {
            swal({
                title: "Parabéns",
                text: "Status alterado com sucesso!.",
                icon: "success",
            })
        });
    });


    $("body").on('click', '.edit', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $.get(url, function(data) {
            console.log(data);
            $("#collapse-Cupom").addClass('show');
            $("#formStore").attr('action', "{{route('admin.cupons.update')}}");
            $("#formStore").append('<input type="hidden" name="_method" value="PUT">');
            $("#formStore input[name='id']").val(data.id);
            $("#formStore input[name='codigo']").val(data.codigo);
            $("#formStore input[name='qtd']").val(data.qtd);
            $("#formStore select[name='modalidade']").val(data.modalidade);
            $("#formStore select[name='id_franquia']").val(data.id_franquia);
            $("#formStore select[name='tipo']").val(data.tipo);
            $("#formStore select[name='tipo_franqueado']").val(data.tipo_franqueado);
            $("#formStore input[name='valor']").val(data.valor);
            $("#formStore input[name='valor_minimo']").val(data.valor_minimo);
            $("#formStore").addClass('show');

            if (data.tipo_franqueado === 'trip') {
                $('#id_franquia').prop('disabled', true);
                $('#card_franquia').css('display', 'none');
            } else {
                $('#id_franquia').prop('disabled', false);
                $('#card_franquia').css('display', 'block');
            }

        })
    })

$("#formStore").submit(function (e) {
    e.preventDefault();
    $("span.error").remove()
    $.ajax({
        type: "POST",
        url: $(this).attr('action'),
        data: $(this).serialize(),
        success: function (data) {
            console.log(data);
            if (data === "editado") {
                swal({
                    title: "Parabéns",
                    text: "Editado com sucesso!.",
                    icon: "success",
                })
                window.location.reload();
            } else {
                swal({
                    title: "Parabéns",
                    text: "Cadastro realizado com sucesso!.",
                    icon: "success",
                })

                $("#formStore")[0].reset();
                $(".disabled").remove();
                $('#collapse-Cupom').collapse('hide');
                window.location.reload();
            }
        },
        error: function (err) {
            console.log(err);

            if (err.status == 422) { // when status code is 422, it's a validation issue
                console.log(err.responseJSON);
                $('#success_message').fadeIn().html(err.responseJSON.message);
                // you can loop through the errors object and show it to the user
                console.warn(err.responseJSON.errors);
                // display errors on each form field
                $.each(err.responseJSON.errors, function (i, error) {
                    var el = $(document).find('[name="' + i + '"]');
                    el.after($('<span class="error" style="color: red;">' + error[0] +
                        '</span>'));
                });
            }
        }
    })
})
</script>
@endsection