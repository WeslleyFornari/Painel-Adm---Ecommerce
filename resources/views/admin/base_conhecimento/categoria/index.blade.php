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

.cadastro_categoria{
        display: none;
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
                        <h4>Base Conhecimento Categoria</h4>          
                    </div>
                    <div class="col-5 my-2 pe-4 text-end">
                        <button id="cadastro_categoria" class="btn btn-primary float-end mb-0 me-3 ">
                            Cadastro
                        </button>
                    </div>
                </div>
            </div>    
            <div class="card-body px-0 pt-0 pb-2 cadastro_categoria">
                <form action="{{route('admin.base_conhecimento_categoria.store')}}" id="formStore" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="container row">
                    <input type="hidden" name="id">
                    <div class="form-group col-sm-4">
                        <span class="titulo"> Título: *</span>
                        <input type="text" name="titulo" value="" class="form-control" required>  
                    </div>
                    <div class="form-group col-sm-4">
                        <span class="titulo"> Tipo: </span>
                        <select class="form-select" name="tipo">
                            <option value="" selected>Selecione</option>
                            <option value="toy">Toy</option>
                            <option value="trip">Trip</option>
                        </select>
                    </div>
                    <div class="col-sm-4 col-12 mt-3 text-end">
                        <button type="submit" class="btn btn-primary mt-2">Salvar</button>
                        <button type="button" class="btn btn-secondary mt-2" id="close">Cancelar</button>
                    </div>
                </div>
                </form>
            </div>

            <div id="lista-categorias">
                @include('admin.base_conhecimento.categoria._list')
            </div> 
            {{ $base_conhecimento_categorias->links() }}
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script>
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
                }).then(function() {
                    location.reload();
                });
            } else {
                swal({
                    title: "Parabéns",
                    text: "Cadastro realizado com sucesso!.",
                    icon: "success",
                }).then(function() {
                    location.reload();
                });
            }
            $("#formStore")[0].reset();
            $(".disabled").remove();
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

$("body").on('click', '.edit', function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.get(url, function (data) {
        console.log(data)
        if ($(".cadastro_categoria").css("display") === "none") {
            $(".cadastro_categoria").show();
        }
        $("#formStore").attr('action', '{{route('admin.base_conhecimento_categoria.update')}}');
        $("#formStore").append('<input type="hidden" name="_method" value="PUT">');
        $("#formStore input[name='id']").val(data.id);
        $("#formStore input[name='titulo']").val(data.titulo);
        $("#formStore select[name='tipo']").val(data.tipo);
        $("#formStore").addClass('show');
       
    })
})

$(document).ready(function() {
    $("#cadastro_categoria").click(function() {
    if ($(".cadastro_categoria").css("display") === "none") {
        $(".cadastro_categoria").show();
    }
    $("#formStore")[0].reset();
    });
});

$(document).ready(function() {
    $("#close").click(function() {
        $(".cadastro_categoria").hide();
        $("#formStore").find('input[name="_method"]').remove(); 
        $("#formStore").attr('action', '{{route('admin.base_conhecimento_categoria.store')}}');
    });
});

$("body").on('change', '.status-categoria', function (e) {
    e.preventDefault();
    var id = this.getAttribute('data-id');
    console.log("O estado do checkbox mudou! " + id);

    var url = '{{ route("admin.base_conhecimento_categoria.mudarStatus", ["id" => ":id"]) }}';
    url = url.replace(':id', id);
    $.get(url, function (data) {
        swal({
            title: "Parabéns",
            text: "Status alterado com sucesso!.",
            icon: "success",
        })
    });
});

    
  </script>
@endsection







  