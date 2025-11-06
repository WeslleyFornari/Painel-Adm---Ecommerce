@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="col-7">
                    <h4>Cadastro Região Atendida</h4>
                </div>
                <form action="{{route('admin.regiao_atendida.store')}}" id="formStore" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mt-4">

                        <input type="hidden" name="id">
                        <div class="row col-12">
                            <span class="titulo"> Tipo: *</span>
                            <div class="col-3">
                                <input type="radio" name="tipo" value="bairro" checked id="tipo">
                                <label for="bairro">Bairro</label><br>
                            </div>
                            <div class="col-3">
                                <input type="radio" name="tipo" value="cidade" id="tipo">
                                <label for="cidade">Cidade</label><br>
                            </div>
                        </div>

                        <p id="observacao" style="display: none; margin-bottom: 0">Observação: Ao selecionar a cidade, você define um único valor de entrega que será aplicado a toda a área da cidade.</p>
                        <div class="form-group col-sm-12 mt-2" id="content_bairro">
                            <span class="titulo"> Bairro: *</span>
                            <input id="bairro" type="text" name="bairro" class="form-control" placeholder="Digite o bairro ou CEP. ex: 12230-085">
                        </div>

                        <div class="form-group col-sm-12 mt-2" style="display: none" id="content_cidade">
                            <span class="titulo"> Cidade: *</span>
                            <input id="cidade" type="text" name="cidade" class="form-control" placeholder="Digite a cidade">
                        </div>

                        @if (Auth::user()->role != 'franqueado')
                        <div class="form-group col-sm-4">
                            <span class="titulo"> Valor Entrega Expresso: *</span>
                            <input type="text" name="valor_entrega_expresso" value="" class="form-control moneyMask" required>
                        </div>
                        <div class="form-group col-sm-4">
                            <span class="titulo"> Valor Entrega Econômico: *</span>
                            <input type="text" name="valor_entrega_economico" value="" class="form-control moneyMask" required>
                        </div>
                        <div class="form-group col-sm-4">
                            <span class="titulo"> Tempo de Entrega:</span>
                            <input type="text" name="tempo_entrega" value="" class="form-control">
                        </div>
                        @else
                        @if (Auth::user()->franquia->frete_expresso == 'sim')
                        <div class="form-group col-sm-4">
                            <span class="titulo"> Valor Entrega Expresso: *</span>
                            <input type="text" name="valor_entrega_expresso" value="" class="form-control moneyMask" required>
                        </div>
                        @endif

                        @if (Auth::user()->franquia->frete_economico == 'sim')
                        <div class="form-group col-sm-4">
                            <span class="titulo"> Valor Entrega Econômico: *</span>
                            <input type="text" name="valor_entrega_economico" value="" class="form-control moneyMask" required>
                        </div>
                        @endif

                        @if (Auth::user()->franquia->frete_expresso == 'sim' || Auth::user()->franquia->frete_economico == 'sim')
                        <div class="form-group col-sm-4">
                            <span class="titulo"> Tempo de Entrega:</span>
                            <input type="text" name="tempo_entrega" value="" class="form-control">
                        </div>
                        @endif
                        @endif
                        @if (Auth::user()->role != 'franqueado')
                        <div class="form-group col-sm-4">
                            <span class="titulo"> Franquias: </span>
                            <select class="form-select" name="id_franqueado">
                                <option value="">Selecione</option>
                                @foreach($selecionarFranquia as $franquias)
                                <option value="{{$franquias->id}}">{{ $franquias->nome_franquia}}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                    </div>

                    <input type="hidden" id="bairro_selecionado" name="bairro_selecionado">
                    <input type="hidden" id="cidade_selecionado" name="cidade_selecionado">
                    <input type="hidden" id="estado_selecionado" name="estado_selecionado">

                    <div class="row">
                        <div class=" col-sm-6 mt-4">
                            <a href="{{route('admin.regiao_atendida.index')}}" class="btn btn-warning">Voltar</a>
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
    function initAutocomplete() {
        var input = document.getElementById('bairro');
        var options = {
            types: ['(regions)'],
            componentRestrictions: {
                country: 'BR'
            }
        };
        var autocomplete = new google.maps.places.Autocomplete(input, options);

        autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();

            if (!place.address_components) {
                return;
            }

            let bairro = '';
            let cidade = '';
            let estado = '';

            place.address_components.forEach(component => {
                if (component.types.includes('sublocality_level_1') || component.types.includes('locality')) {
                    bairro = component.long_name;
                }
                if (component.types.includes('administrative_area_level_2')) {
                    cidade = component.long_name;
                }
                if (component.types.includes('administrative_area_level_1')) {
                    estado = component.short_name;
                }
            });

            document.getElementById('bairro_selecionado').value = bairro;
            document.getElementById('cidade_selecionado').value = cidade;
            document.getElementById('estado_selecionado').value = estado;
        });
    }

    google.maps.event.addDomListener(window, 'load', initAutocomplete);
</script>

<script>
    function initAutocompleteCidade() {
        var input = document.getElementById('cidade');
        var options = {
            types: ['(cities)'],
            componentRestrictions: {
                country: 'BR'
            }
        };
        var autocomplete = new google.maps.places.Autocomplete(input, options);

        autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();

            if (!place.address_components) {
                return;
            }

            let cidade = '';
            let estado = '';

            place.address_components.forEach(component => {
                if (component.types.includes('locality') || component.types.includes('administrative_area_level_2')) {
                    cidade = component.long_name;
                }
                if (component.types.includes('administrative_area_level_1')) {
                    estado = component.short_name;
                }
            });
            document.getElementById('cidade_selecionado').value = cidade;
            document.getElementById('estado_selecionado').value = estado;
        });
    }

    google.maps.event.addDomListener(window, 'load', initAutocompleteCidade);
</script>


<script>
    $("body").on('change', '#tipo', function(e) {
        e.preventDefault();
        var tipo = $('input[name="tipo"]:checked').val();

        console.log(tipo);

        if (tipo == 'bairro') {
            $('#content_bairro').css('display', 'block');
            $('#content_cidade').css('display', 'none');
            $('#observacao').css('display', 'none');
        } else {
            $('#content_bairro').css('display', 'none');
            $('#content_cidade').css('display', 'block');
            $('#observacao').css('display', 'block');
        }
    });
    $("#formStore").submit(function(e) {

        e.preventDefault();
        $("span.error").remove();

        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: $(this).serialize(),

            success: function(data) {
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
            error: function(err) {
                console.log(err);

                if (err.status == 422) { // when status code is 422, it's a validation issue
                    console.log(err.responseJSON);
                    $('#success_message').fadeIn().html(err.responseJSON.message);
                    // you can loop through the errors object and show it to the user
                    console.warn(err.responseJSON.errors);
                    // display errors on each form field
                    $.each(err.responseJSON.errors, function(i, error) {
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