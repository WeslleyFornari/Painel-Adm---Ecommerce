@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="col-7">
                    <h4>Cadastro CEPs Bloqueados</h4>          
                </div>

            <form action="{{route('admin.cep_bloqueados.store')}}" id="formStore" method="POST" enctype="multipart/form-data">
                @csrf
                    <div class="row mt-4">
                        @if (Auth::user()->role != 'franqueado')
                        <div class="form-group col-sm-4">
                            <span class="titulo"> Franquias*: </span>
                            <select class="form-select" name="id_franqueado">
                                <option value="">Selecione</option>
                                @foreach($selecionarFranquia as $franquias)
                                    <option value="{{$franquias->id}}">{{ $franquias->nome_franquia}}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div class="col-12 col-sm-5">
                            <label for="">CEP *</label>
                            <div class="input-group ">
                                <input type="text" class="form-control cepMask border-radius-bottom-end-0" name="cep" id="buscaCep" required>
                                <button class="btn btn-outline-primary mb-0" type="button" >  <i class="fa fa-search"></i></button>
                            </div>  
                        </div>
                        <div class="form-group col-sm-6">
                            <span class="titulo"> Rua: *</span>
                            <input id="rua" type="text" name="endereco" class="form-control" placeholder="Pesquisar por rua...">
                        </div>
                        <div class="form-group col-sm-6">
                            <span class="titulo"> Bairro: *</span>
                            <input id="bairro" type="text" name="bairro" class="form-control">
                        </div>
                        <div class="form-group col-sm-4">
                            <span class="titulo"> Cidade: *</span>
                            <input id="cidade" type="text" name="cidade" class="form-control">
                        </div>
                        <div class="form-group col-sm-4">
                            <span class="titulo"> Estado: *</span>
                            <input id="estado" type="text" name="estado" class="form-control">
                        </div>
                        <div class="form-group col-sm-4">
                            <span class="titulo"> País: *</span>
                            <input id="pais" type="text" name="pais" class="form-control">
                        </div>
                     </div>
                        
                        <div class="row">
                            <div class=" col-sm-6 mt-4">
                                <a href="{{route('admin.cep_bloqueados.index')}}" class="btn btn-warning">Voltar</a>
                            </div>
                            <div class="col-sm-6 text-end mt-4 pe-4">
                                <button type="submit" class="btn btn-primary">Salvar</button>
                            </div>
                        </div>
                    
                </form>
           </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>



<script>
  // JavaScript
function initAutocomplete() {
    var input = document.getElementById('rua');
    var options = {
        types: ['address']
    };
    var autocomplete = new google.maps.places.Autocomplete(input, options);

    autocomplete.addListener('place_changed', function() {
        var place = autocomplete.getPlace();
        var rua = '';
        var bairro = '';
        var cidade = '';
        var cep = '';
        var estado = '';
        var pais = '';

        // Itera sobre os componentes do endereço e os atribui aos campos apropriados
        for (var i = 0; i < place.address_components.length; i++) {
            var addressType = place.address_components[i].types[0];

            if (addressType === 'route') {
                rua = place.address_components[i].long_name;
            }

            if (addressType === 'sublocality_level_1' || addressType === 'locality') {
                bairro = place.address_components[i].long_name;
            }

            if (addressType === 'administrative_area_level_2') {
                cidade = place.address_components[i].long_name;
            }

            if (addressType === 'postal_code') {
                cep = place.address_components[i].long_name;
            }

            if (addressType === 'administrative_area_level_1') {
                estado = place.address_components[i].long_name;
            }

            if (addressType === 'country') {
                pais = place.address_components[i].long_name;
            }
        }

        document.getElementById('rua').value = rua;
        document.getElementById('bairro').value = bairro;
        document.getElementById('cidade').value = cidade;
        document.getElementById('buscaCep').value = cep;
        document.getElementById('estado').value = estado;
        document.getElementById('pais').value = pais;
    });
}

google.maps.event.addDomListener(window, 'load', initAutocomplete);


</script>

<script>
$("#formStore").submit(function (e) {

    e.preventDefault();
    $("span.error").remove();

    $.ajax({
        type: "POST",
        url: $(this).attr('action'),
        data: $(this).serialize(),

        success: function (data) {
            swal({
                title: "Parabéns",
                text: "Cadastro realizado com sucesso!.",
                icon: "success",
            }).then(function() {
                location.reload();
            });
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
    });
});
</script>
@endsection
  