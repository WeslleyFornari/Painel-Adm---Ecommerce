@extends('layouts.app')

@section('content')
<div class="row justify-content-center">

<form action="{{route('admin.banners.store')}}" id="formStore" method="POST" enctype="multipart/form-data">

@csrf

    <div class="col-md-12">
       
            <div class="card">
                <div class="card-body">

                <div class="row mb-4 border-bottom">
                        <div class="col-6">
                            <h4>Cadastro</h4>
                        </div>
      
                    </div>

                    <div class="row ms-5 arial14-font">

                        <div class="col">
                            <div class="row mt-2">
                                <div class="form-group col-sm-6">
                                    <span class="titulo"> Titulo: *</span>
                                    <input type="text" name="titulo" id="titulo" class="form-control" required>
                                </div>
                                <div class="form-group col-sm-5">
                                    <span class="titulo"> URL: *</span>
                                    <input type="url" name="url" id="url" class="form-control" required>
                                </div>
                                <div class="form-group col-sm-2 me-5">
                                    <span class="titulo"> Ordem: *</span>
                                    <input type="number" name="ordem" id="ordem" class="form-control" required>
                                </div>
                                
                                <div class="form-group col-sm-2 me-5">
                                    <span class="titulo"> Nova Janela: *</span>
                                    <select name="new_window" class="form-select" required>
                                        <option value="">Selecione</option>
                                        <option value="sim">SIM</option>
                                        <option value="não">NÃO</option>
                                    
                                   </select>
                                </div>

                                <div class="col-12 col-sm-2">
                                    <label for="">Tipo * </label>

                                    <select class="form-select"  name="tipo" required>
                                        <option value="">Selecione</option>
                                        <option value="banner">Banner</option>
                                        <option value="pop-up">POP-UP</option>
                                        <!-- <option value="user">Usuário</option> -->
                                    </select>
                                </div>

                                <div class="col-12 col-sm-2">
                                    <label for="">Tipo Franqueado* </label>

                                    <select class="form-select"  name="tipo_franqueado" required>
                                        <option value="">Selecione</option>
                                        <option value="trip">TRIP</option>
                                        <option value="toy">TOY</option>
                                    </select>
                                </div>

                                <div class="form-group col-sm-3 mt-1 me-5">
                                    <span class="titulo"> Status: </span>
                                    <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="status" role="switch" value="ativo" id="flexSwitchCheckChecked" checked>
                                        <label class="form-check-label" for="flexSwitchCheckChecked">Ativo</label>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>


                </div>

            </div>
            </div>

    <div class="row mt-3">

        <div class="col-md-6">
            <div class="card">
            <div class="card-body p-2">

            <div class="my-2 ms-5">
                        <label for="exampleInputFile" class="control-label">
                        Imagem Desktop <small>(500 x 500px)</small> <br>
                        <span id="desktop-error" class="error border-0 font-weight-bold" style="color: red; display: none; font-size: 12px">Imagem de desktop é obrigatória.</span>
                    </label>
                    <x-upload-file target="logo" collum="id_media_desktop" type="single"/>

            </div>
               
            </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
            <div class="card-body p-2">
            
            <div class="my-2 ms-5" style="margin-left:140px;">
                    <label for="exampleInputFile" class="control-label">
                     Imagem Mobile <small>(500 x 500px)</small> <br>
                     <span id="mobile-error" class="error border-0 font-weight-bold" style="color: red; display: none; font-size: 12px">Imagem de mobile é obrigatória.</span>
                    </label>
                    <x-upload-file target="logo" collum="id_media_mobile" type="single"/>
                </div>
            </div>
            </div>
        </div>

    </div>


        <div class="card mt-3">
            <div class="card-body">
            
                <div class="row">
                    <div class="col"> 
                        <a class="btn btn-secondary m-0" href="{{route('admin.banners.index')}}"><i class="fa fa-fw fa-lg fa-arrow-left"></i> Voltar</a>
                    </div>
                    <div class="col text-end">
                    <button class="btn btn-success m-0" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> Salvar</button>
                    </div>
                </div>
            </div>
        </div>
        

</form>
</div>
@endsection

@section('scripts')
<script>

function isValidURL(url) {
    const pattern = new RegExp('^(https?:\\/\\/)?' + 
        '((([a-zA-Z0-9$_.+!*\'(),;?&=-]+)@)?[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,6}|' + 
        'localhost|' + 
        '\\d{1,3}\\.(\\d{1,3}\\.){2}\\d{1,3})' + 
        '(\\:\\d{1,5})?' + 
        '(\\/[-a-zA-Z0-9%_.~+!*\'(),;:@&=$/?#]*)*' + 
        '(\\?[;&a-zA-Z0-9%_.~+=-]*)?' + 
        '(#[-a-zA-Z0-9_]*)?$'); 
    return !!pattern.test(url);
}

$("#url").on('input', function() {
    const urlInput = $(this).val();
    const errorElement = $(this).next('.error');
    if (errorElement.length) {
        errorElement.remove(); 
    }

    if (urlInput.length > 0 && !isValidURL(urlInput)) {
        $(this).after('<span class="error border-0" style="color: red; font-size:12px; font-weight: bold;">URL inválida</span>');
    }
});

$("#formStore").submit(function (e) {
    e.preventDefault();

    // Escondendo spans de erro
    $("span.error").hide();

    $.ajax({
        type: "POST",
        url: $(this).attr('action'),
        data: $(this).serialize(),

        success: function (data) {
            let message = "Cadastro realizado com sucesso!";
            
            if (data.countBanner > 0) {
                message = `${data.countBanner} pop-ups foram inativados e o banner foi cadastrado com sucesso!`;
            }

            swal({
                title: "Parabéns",
                text: message,
                icon: "success",
            }).then(function () {
                window.location.href = '{{ route("admin.banners.index") }}';
            });
        },

        error: function (err) {
            if (err.status == 422) {
                $.each(err.responseJSON.errors, function (i, error) {
                    if (i === 'id_media_desktop') {
                        $("#desktop-error").show().text(error[0]);
                    } else if (i === 'id_media_mobile') {
                        $("#mobile-error").show().text(error[0]);
                    } else {
                        var el = $(document).find('[name="' + i + '"]');
                        el.after('<span class="error border-0" style="color: red; font-size: 12px; font-weight: bold;">' + error[0] + '</span>');
                    }
                });
            }
        }
    });
});

</script>
@endsection