@extends('layouts.app')

@section('content')
<div class="row justify-content-center">

<form id="formStore" action="{{route('admin.produtos_categorias.store')}}">
@csrf

    <div class="col-md-12">
       
            <div class="card">
            <div class="card-body">

                <div class="row mb-4 border-bottom">
                        <div class="col-6">
                            <h4>Cadastro</h4>
                        </div>
      
                    </div>

                    <div class="row ms-5 arial14-font mb-4">

                        <div class="col-6 pe-5">
                            <input type="hidden" name="id">

                            <div class="form-group">
                                <span class="titulo"> Nome: *</span>
                                <input type="text" name="nome" value="" class="form-control" required>  
                            </div>
                            <div class="form-group">
                                <span class="titulo"> Tipo:</span>
                                <select class="form-select" name="tipo" id="tipo">
                                    <option value="trip">Trip</option>
                                    <option value="toy">Toy</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <span class="titulo"> Descriçao: *</span>
                                <input type="text" name="descricao" value="" class="form-control" required>  
                            </div>
                            <div class="form-group">
                                <span class="titulo"> Categoria Relacionada: </span>
                                <select class="form-select" name="id_parent">
                                    <option value="">Selecione</option>
                                
                            </select>
                            </div>
                        </div>

                        <div class="col-6 ps-5">
                            <div class="form-group">
                                <label for="exampleInputFile" class="control-label"> Imagem Categoria <small>(500 x 500px)</small></label>
                                <x-upload-file target="logo" collum="id_media" type="single" />
                            </div>
                        </div>
                      
                    </div>


                    <div class="row">
                            <div class="col ms-3"> 
                                <a class="btn btn-warning m-0" href="{{route('admin.produtos_categorias.index')}}"><i class="fa fa-fw fa-lg fa-arrow-left"></i> Voltar</a>
                            </div>
                            <div class="col text-end me-3">
                            <button class="btn btn-success m-0" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> Salvar</button>
                    </div>
                </div>

            </div>
            </div>
    </div>       

</form>
</div>
@endsection

@section('scripts')
<script>

// STORE
    $("#formStore").submit(function (e) {

        e.preventDefault();
        $("span.error").remove()

        $.ajax({

            type: "POST",
            url: $(this).attr('action'),
            data: $(this).serialize(),

            success: function (data) {
                console.log(data);
            
                swal({
                    title: "Parábens",
                    text: "Cadastro realizado com sucesso!.",
                    icon: "success",
                }).then(function() {

                   // location.reload();
                    window.location.href = '{{route("admin.produtos_categorias.index")}}';
                });
            },
            error: function (err) {
                console.log(err);

                if (err.status == 422) { 
                    console.log(err.responseJSON);
                    $('#success_message').fadeIn().html(err.responseJSON.message);
                   
                    console.warn(err.responseJSON.errors);
                   
                    $.each(err.responseJSON.errors, function (i, error) {
                        var el = $(document).find('[name="' + i + '"]');
                        el.after($('<span class="error" style="color: red; font-size:12px; font-weight: bold; margin-left:10px; border: none;">' + error[0] +
                            '</span>'));
                    });
                }
            }
        })
    })


</script>
@endsection