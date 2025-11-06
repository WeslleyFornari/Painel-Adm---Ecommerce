@extends('layouts.app')
@section('assets')
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css" rel="stylesheet">
@endsection
@section('content')
<div class="row justify-content-center">

<form id="formStore" action="{{route('admin.depoimentos.store')}}" method="POST" enctype="multipart/form-data">
@csrf
    
        <div class="card mt-3">
             <div class="card-body">

                <div class="row mb-4 border-bottom">
                        <div class="col-6">
                            <h4>Cadastrar</h4>
                        </div>
                </div>

                <div class="row">
                        <div class="form-group col-sm-3 ps-4 mt-2">
                              
                                <div class="form-check form-switch">
                                <span class="titulo"> Status: </span><input class="form-check-input" type="checkbox" name="status" role="switch" value="ativo" id="flexSwitchCheckChecked" checked>
                                    <label class="form-check-label" for="flexSwitchCheckChecked">Ativo</label>
                                </div> 
                        </div>

                        <div class="form-group inline col-sm-4 d-flex align-items-center">
                           
                            <span class="titulo me-4"> Tipo: </span>          
                            <select name="tipo_franqueado" required id="" class="form-select">
                                <option value="">Selecione</option>
                                <option value="toy">TOY</option>
                                <option value="trip">TRIP</option>
                            </select>
                        </div>  
                   </div>

                    <div class="row mt-2">
                    <!-- CONTEUDO SUMMER NOTE -->
                        <div class="col-8">                               
                                <textarea id="summernote" name="texto" class="form-control"  placeholder="Digite o conteudo do assunto" style="display: block;"></textarea>
                        </div>

                        <div class="col-4 text-center">
                                <label for="exampleInputFile" class="control-label">
                                Imagem<small>(250 x 250px)</small>
                            </label>
                            <x-upload-file target="logo" collum="id_foto" type="single" />
                        </div>
      
                    </div>

                    <div class="row mt-4">
                            <div class="col-8 ps-3"> 
                                <a class="btn btn-light m-0" href="{{route('admin.depoimentos.index')}}"><i class="fa fa-fw fa-lg fa-arrow-left"></i> Voltar</a>
                            </div>
                            <div class="col-4 text-end pe-3">
                            <button class="btn btn-success m-0" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> Salvar</button>
                        </div>
                    </div>

             </div>
        </div>
</form>
</div>

@endsection


@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>
<script>

$('#summernote').summernote({
       
        tabsize: 1,
        height: 150,
        
      });


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

                window.location.href = '{{route("admin.depoimentos.index")}}';
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
    });

    
</script>
@endsection