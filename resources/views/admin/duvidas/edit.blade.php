@extends('layouts.app')

@section('assets')
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="row justify-content-center">

<form id="formStore" action="{{route('admin.duvidas.update', ['id' => $duvida->id])}}" method="POST" enctype="multipart/form-data">
@csrf
    
        <div class="card mt-3">
             <div class="card-body">

                <div class="row mb-4 border-bottom">
                        <div class="col-6">
                            <h4>Editar</h4>
                        </div>
                </div>

                <div class="row">
                        <div class="form-group col-sm-6 ps-4 mt-2 justify-content-end">
                              
                            <div class="form-check form-switch">
                                    <input class="form-check-input status" type="checkbox" name="status" role="switch" value="ativo"
                                        data-id="{{$duvida->id}}" @if($duvida->status == 'ativo') checked @endif>
                                <label class="form-check-label" for="flexSwitchCheckChecked">Ativo</label>
                            </div>
                        </div>

                        <div class="form-group inline col-sm-4 d-flex align-items-center">
                           
                            <span class="titulo me-4"> Franquia: </span>          
                            <select name="tipo_franqueado" required id="" class="form-select">
                                <option value="">Selecione</option>
                                <option value="toy" @if($duvida->tipo_franqueado == 'toy') selected @endif>TOY</option>
                                <option value="trip" @if($duvida->tipo_franqueado == 'trip') selected @endif>TRIP</option>
                            </select>
                        </div>  
                   </div>

                    <div class="row mt-1">

                        <div class="col-12 mx-auto">
                        <span class="titulo me-4"> Pergunta * </span>   
                        </div>
                        <div class="col-12 mb-4">
                            <input type="text" class="form-control" name="pergunta" value="{{$duvida->pergunta}}" required>
                        </div>
                    <!-- CONTEUDO SUMMER NOTE -->
                        <div class="col-12">                               
                                <textarea id="summernote" name="resposta" class="form-control"  placeholder="Digite sua resposta" style="display: block;">{{$duvida->resposta}}</textarea>
                        </div>

                        
      
                    </div>

                    <div class="row mt-4">
                            <div class="col-8 ps-3"> 
                                <a class="btn btn-light m-0" href="{{route('admin.duvidas.index')}}"><i class="fa fa-fw fa-lg fa-arrow-left"></i> Voltar</a>
                            </div>
                            <div class="col-4 text-end pe-3">
                            <button class="btn btn-success m-0" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> Atualizar</button>
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
        height: 100,
        
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
                text: "Cadastro atualizado com sucesso!.",
                icon: "success",
            }).then(function() {

                window.location.href = '{{route("admin.duvidas.index")}}';
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