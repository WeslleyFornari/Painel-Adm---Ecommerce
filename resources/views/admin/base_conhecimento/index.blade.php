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
                        <h4>Base Conhecimento</h4>          
                    </div>
                    <div class="col-5 my-2 pe-4 text-end">
                        <a href="{{route('admin.base_conhecimento_categoria.index')}}" class="btn btn-secondary">
                        Categoria 
                        </a>
                        <button type="button" class="btn btn-primary cadastro" data-bs-toggle="modal" data-bs-target="#modalBaseConhecimento">
                        Cadastrar 
                        </button>
                    </div>
                </div>
                <div id="lista-Produtos">
                    @include('admin.base_conhecimento._list')
                </div> 
                {{ $bases_conhecimento->links() }}
           </div>
        </div>
        

    </div>
</div>

<div class="modal fade" id="modalBaseConhecimento" tabindex="-1" aria-labelledby="modalBaseConhecimentoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalBaseConhecimentoLabel">Base Conhecimento</h5>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
      </div>
      <div class="modal-body">
        <form action="{{route('admin.base_conhecimento.store')}}" id="formStore" method="POST" enctype="multipart/form-data">
        @csrf
            <div class="row">
                <input type="hidden" name="id">
                <div class="form-group col-sm-12">
                    <span class="titulo"> Título: *</span>
                    <input type="text" name="titulo" value="" class="form-control" required>  
                </div>
                <div class="form-group col-sm-12">
                    <span class="titulo"> Descriçao: *</span>
                    <input type="text" name="descricao" value="" class="form-control" required>  
                </div>
                <div class="form-group col-sm-12">
                    <span class="titulo"> Tipo: </span>
                    <select class="form-select" name="tipo">
                        <option value="" selected>Selecione</option>
                        <option value="toy">Toy</option>
                        <option value="trip">Trip</option>
                   </select>
                </div>
                <div class="form-group col-sm-12">
                    <span class="titulo"> Categoria: </span>
                    <select class="form-select" name="id_categoria">
                        <option value="">Selecione</option>
                        @foreach($selectcategorias as $categoria)
                            <option value="{{$categoria->id}}">{{ $categoria->titulo}}</option>
                        @endforeach
                   </select>
                </div>
            </div>
        
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Salvar</button>
      </div>
      </form>
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

        $("#modalBaseConhecimento").modal('show');

        $("#formStore").attr('action', '{{ route("admin.base_conhecimento.update") }}');
        $("#formStore").append('<input type="hidden" name="_method" value="PUT">');
        $("#formStore input[name='id']").val(data.id);
        $("#formStore input[name='titulo']").val(data.titulo);
        $("#formStore input[name='descricao']").val(data.descricao);
        $("#formStore select[name='id_categoria']").val(data.id_categoria);
        $("#formStore select[name='tipo']").val(data.tipo);

        
        $("#formStore").addClass('show');
       
    })
})

$("body").on('click', '.cadastro', function (e) {
    e.preventDefault();
       $("#modalBaseConhecimento").modal('show');
})
 
$("body").on('click', '.close', function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.get(url, function (data) {
        console.log(data)

        $("#modalBaseConhecimento").modal('hide');
       
    })
})

$('#modalBaseConhecimento').on('hidden.bs.modal', function () {
    $("#formStore")[0].reset();
});

$("body").on('change', '.status-categoria', function (e) {
    e.preventDefault();
    var id = this.getAttribute('data-id');
    console.log("O estado do checkbox mudou! " + id);

    var url = '{{ route("admin.base_conhecimento.mudarStatus", ["id" => ":id"]) }}';
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







  