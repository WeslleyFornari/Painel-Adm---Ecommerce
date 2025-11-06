@extends('layouts.app')

@section('title', 'Marcas')


@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row my-2">
                        <div class="col-6">
                            <h4>Lista</h4>
                        </div>
                        @if(permissao('marcas')->criar == 'sim')
                        <div class="col-6 text-end px-4">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#modalMarca">Cadastrar</button>
                        </div>
                        @endif
                        
                    </div>




                    <div id="lista-Marca" class="">
                        @include('admin.marcas._lista')
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalMarca" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Marca</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="conteudo-marcas">
                @include('admin.marcas._cadastrar')
            </div>

        </div>

    </div>
</div>
</div>
@endsection

@section('scripts')
<script>
    $("body").on('submit', '.formStore', function(e) {

        e.preventDefault();
        $("span.error").remove()

        $.ajax({

            url: $(this).attr('action'),
            type: "POST",
            data: $(this).serialize(),

            success: function(response) {

                $(".formStore")[0].reset();
                $("#modalMarca").modal('hide');
                location.reload();

            },

            error: function(err) {
                console.log(err);

                if (err.status == 422) {
                    console.log(err.responseJSON);
                    $('#success_message').fadeIn().html(err.responseJSON.message);

                    console.warn(err.responseJSON.errors);

                    $.each(err.responseJSON.errors, function(i, error) {
                        var el = $(document).find('[name="' + i + '"]');
                        el.after($('<span class="error" style="color: red; font-size:12px; font-weight: bold; margin-left:10px; border: none;">' + error[0] +
                            '</span>'));
                    });
                }
            }
        });
    });


    $("body").on('click', '.editMarca', function(e) {

        e.preventDefault();
        $.ajax({
            url: $(this).attr('href'),
            type: "GET",

            success: function(response) {

                $("#conteudo-marcas").html(response);
                $("#modalMarca").modal('show');
            },
        });
    });
</script>

@endsection