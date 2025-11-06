@extends('layouts.app')
@section('assets')
    <style>
        .number-input {
    display: flex;
    align-items: center;
}

.number-input input[type="number"] {
    -moz-appearance: textfield;
    width: 30px;
    text-align: center;
    border: none;
    padding: 5px;
    background-color: transparent;
}

.number-input button {
    background-color: transparent;
    border: none;
    width: 30px;
    height: 30px;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    padding: 0;
}

.number-input button:focus,
.number-input input:focus {
    outline: none;
}

input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none; 
    margin: 0; 
}

input[type=number] {
    -moz-appearance: textfield;
}
    </style>
@endsection
@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.pedidos.store') }}" id="formStore" method="POST" enctype="multipart/form-data">
                    @csrf
                    <a data-toggle="collapse" href="#etapa1" role="button" aria-expanded="true" aria-controls="etapa1">
                        1- Franquia
                    </a>
                    <div class="collapse show" id="etapa1">
                        @if (Auth::user()->role != 'franqueado')
                        <div class="row mt-3" id="selecionefranquia">
                            <div class="form-group col-sm-5">
                                <select class="form-select select2" name="franquia" id="franquia">
                                    <option value="">Selecione a franquia</option>
                                    @foreach($franquias as $franquia)
                                        <option value="{{ $franquia->id }}">{{ $franquia->nome_franquia }}</option>
                                    @endforeach
                                </select>
                                <p class="text-center my-2">ou</p>
                                <div class="d-flex">
                                    <div class="col-9">
                                        <input id="bairro" type="text" name="bairro" class="form-control" placeholder="Pesquise o bairro para selecionar o franquia mais próxima">
                                    </div>
                                    <div class="col-3">
                                        <button class="btn btn-primary w-100" id="buscarFranquia" type="button">Pesquisar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="id_franquia">
                        <input type="hidden" name="tipo_franqueado">
                        <div class="" id="viewFranquia">
                        </div>
                        @else
                        <input type="hidden" name="id_franquia" value="{{Auth::user()->franquia->id}}">
                        <input type="hidden" id="franquia" value="{{Auth::user()->franquia->id}}">
                        <input type="hidden" name="tipo_franqueado" value="{{Auth::user()->franquia->tipo_franqueado}}">
                        <div class="" id="viewFranquia">
                            <h5 class="my-3"> {{Auth::user()->franquia->nome_franquia}} </h5>
                        </div>
                        @endif
                    </div>

                    <a data-toggle="collapse" href="#etapa2" role="button" aria-expanded="false" aria-controls="etapa2" onclick="mudar('etapa2')" class="d-block w-100 mb-3">
                        2- Itens
                    </a>
                    <div class="collapse" id="etapa2">
                        <div class="" id="itensview"></div>
                        <a href="" class="btn btn-sm btn-primary adicionaritens" style="font-size: 15px"><i class="fas fa-plus-circle" style="font-size: 15px"></i> Adicionar Item</a>     
                        <div class="col-12">
                            <div class="col-11 " style="width: 95%;">
                                <div class="row descricao-1" style="align-items: flex-end; padding: 0px 30px">
                                    <div class="col-10 text-end">
                                        <span style="color: #87BEEF">Subtotal(sem frete)</span>

                                        <div class="align-items-center flex-wrap justify-content-end" style="display:flex" id="cardParcial">
                                            <input type="checkbox" id="parcial" name="pagamento" value="parcial">
                                            <label for="parcial">Pagamento Parcial</label><br>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <span class="total" id="textvalorTotal"></span>
                                        <input type="hidden" value="" name="valorparcial" class="moneyMask form-control" id="valorparcial">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <a data-toggle="collapse" href="#etapa3" role="button" aria-expanded="false" aria-controls="etapa3" onclick="mudar('etapa3')">
                        3- Cliente
                    </a>
                    <div class="collapse" id="etapa3">
                    <label for="CPF" style="margin-left:430px; color:red;">Não encontrou o Cliente?</label>
                        <div class="row">
                        <div class="col-5">
                            <select name="id_cliente" class="form-select select2">
                                <option value="">Selecionar o Cliente</option>
                                @foreach ($clientes as $cli)
                                <option value="{{$cli->id}}">{{$cli->name}}</option>
                                @endforeach
                            </select>
                        </div>
                            <div class="col-4">
                               
                                <input type="text" id="cpf" name="cpf" class="form-control cpfMask" placeholder="Digite aqui o CPF">
                                <div id="cpfError" class="text-danger small mt-1" style="display: none;"></div>
                            </div>
                            <div class="col-3" style="margin-top:0px;">
                                <button type="button" class="btn btn-primary" id="filterCliente">filtrar</button>
                                <button type="button" class="btn btn-danger" id="clearFilterBtn">Limpar</button>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3"></div>

                    <a data-toggle="collapse" href="#etapa4" role="button" aria-expanded="false" aria-controls="etapa4" onclick="mudar('etapa4')">
                        4- Calcular Frete
                    </a>
                    <div class="collapse" id="etapa4">
                        <div class="row d-flex">
                            <div class="col-sm-5 col-12">
                                <label for="">CEP</label>
                                <div class="d-flex">
                                    <input type="text" name="cep" id="cep" class="w-50 cepMask form-control">
                                    <button class="w-50 btn-sm btn btn-primary mb-0" id="cepCalcular" type="button">Calcular</button>
                                </div>
                                <div class="" id="cepview"></div>

                                <div class="col-6" id="retirar_loja_view" style="display: none">
                                    <div class="form-check">
                                        <input class="form-check-input tipo_frete" type="radio" name="tipo_frete"
                                            id="flexRadioDefault2" value="retirar_loja">
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            <div class="tipo">Retire e devolva na unidade</div>
                                            <small>Agendar Previamente</small>
                                            <div class="">R$ 00,00</div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tipo-frete mb-sm-0 mb-4 mt-3" style="display: none" id="frete">
                            <div class="col-12">
                                <div class="title mb-4 mb-sm-0">Tipo de Frete</div>
                            </div>
                            <div class="row">
                                <div class="col-sm-2 col-6" id="frete_economico">
                                    <div class="form-check">
                                        <input class="form-check-input tipo_frete" type="radio" name="tipo_frete"
                                            id="flexRadioDefault1" value="economico">
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            <div class="tipo">Econômico</div>
                                            <small>Das 09h às 18h.</small>
                                            <div class="" id="economico"></div>
                                            <input type="hidden" name="valor_economico">
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-2 col-6" id="frete_expresso">
                                    <div class="form-check">
                                        <input class="form-check-input tipo_frete" type="radio" name="tipo_frete"
                                            id="flexRadioDefault2" value="expresso">
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            <div class="tipo">Expresso</div>
                                            <small>Entregas a cada 2 horas. Ex: 09h às 11h, 11h às 13h, 13h às 15h, 15h às 17h.</small>
                                            <div class="" id="expresso"></div>
                                            <input type="hidden" name="valor_expresso">
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-2 col-6" id="retirar_loja">
                                    <div class="form-check">
                                        <input class="form-check-input tipo_frete" type="radio" name="tipo_frete"
                                            id="flexRadioDefault2" value="retirar_loja">
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            <div class="tipo">Retire e devolva na unidade</div>
                                            <small>Agendar Previamente</small>
                                            <div class="">R$ 00,00</div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if (Auth::user()->role != 'franqueado')
                            <div class="row" id="retirada" style="display: none">
                                <div class="title mb-4 mb-sm-0">Selecionar a Unidade de Entrega/Devolução</div>
                                <div class="col-6">
                                    <select name="id_retirada" id="unidade" class="form-select">
                                        <option value="">Selecionar a Unidade</option>
                                        @foreach ($franquiasRetirada as $fra)
                                        <option value="{{$fra->id}}">{{$fra->nome_franquia}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @else
                        <input type="hidden" value="{{Auth::user()->franquia->id}}" name="id_retirada">
                        @endif
                    </div>
                    <div class="mb-3"></div>

                    <div class="row">
                        <div class="col"> 
                            <a class="btn btn-secondary m-0" href="{{route('admin.pedidos.index')}}">Voltar</a>
                        </div>
                        <div class="col text-end">
                            <button type="submit" class="btn btn-success">Salvar</button>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>


<script>

$('.select2').select2({
        theme: 'bootstrap-5'
    });
// Busca por CPF
$("body").on('click','#filterCliente',function(e){

    e.preventDefault();
    $('#cpfError').hide().text('');

    var cpf = $('#cpf').val();
    var url = '{{ route("admin.pedidos.buscaCpf") }}';

$.ajax({
        url: url,
        type: "GET",
        data: {
            cpf: cpf
        },
        success: function(response) {
          
            if(response.success) {
                $('select[name="id_cliente"]').empty();
                $('select[name="id_cliente"]').append('<option value="' + response.usuario.id + '" selected>' + response.usuario.name + '</option>'
                );
                $('.select2').select2({
        theme: 'bootstrap-5'
    });
              
            } else {
                $('#cpfError').text(response.message || 'Nenhum usuário encontrado').show();
                $('#clientSelect').html('<option value="">Selecionar o Cliente</option>');
            }
        },
        error: function(xhr) {
            $('#cpfError').text(xhr.responseJSON?.message || 'Erro na comunicação com o servidor').show();
            $('select[name="id_cliente"]').html('<option value="">Selecionar o Cliente</option>');
        }
    });
});

// Recarrega Clientes
function loadClientes() {
    $.ajax({
        url: '{{ route("admin.pedidos.recarregarClientes") }}',
        type: "GET",
        success: function(response) {
            if(response.success) {
                var $select = $('select[name="id_cliente"]');
                $select.empty().append('<option value="">Selecionar o Cliente</option>');
                $('.select2').select2({
        theme: 'bootstrap-5'
    });
                
                $.each(response.usuarios, function(index, cliente) {
                    $select.append(
                        '<option value="' + cliente.id + '">' + cliente.name + '</option>'
                    );
                });
            }
        },
        error: function(xhr) {
            console.error('Erro ao carregar clientes:', xhr.responseText);
        }
    });
}

$('#clearFilterBtn').click(function() {
    $('#cpf').val(''); 
    $('#cpfError').hide().text('');
    loadClientes();
});


cardParcial();
    var urlFacilitTrip = "{{ $urlFacilitTrip }}";
    var urlFacilitToy = "{{ $urlFacilitToy }}";
$("body").on('change', '.tipo_frete', function () {
    $('#retirada').css('display', 'block');
});
$("body").on('change', '#parcial', function () {
    if ($(this).is(':checked')) {
        $('input[name="valorparcial"]').attr('type', 'text'); 
    } else {
        $('input[name="valorparcial"]').attr('type', 'hidden'); 
    }
});

function cardParcial(){
    console.log('aaaaa');
    var tipo_franqueado = $('input[name="tipo_franqueado"]').val();
    console.log(tipo_franqueado);
    if(tipo_franqueado == 'trip'){
        $('#cardParcial').css('display', 'none');
    }
    else if(tipo_franqueado == 'toy'){
        $('#cardParcial').css('display', 'flex');
    }
}


$("#formStore").submit(function (e) {

    e.preventDefault();
    $("span.error").remove();

    var franquia = $('input[name="id_franquia"]').val();

    $.ajax({
        type: "POST",
        url: $(this).attr('action'),
        data: $(this).serialize(),

        success: function (data) {
            var tipo_franqueado = $('input[name="tipo_franqueado"]').val();
            var url = `${data}`;
            
            Swal.fire({
                title: 'Parabéns',
                html: `
                    <p>Cadastro realizado com sucesso!</p>
                    <div class="px-4 row">
                        <div class="col-8 align-content-center">
                            <input type="text" class="form-control" id="myInput" value="${url}">
                        </div>
                        <div class="col-4 align-content-center">
                            <button id="copyButton" class="btn btn-info w-100 mb-0" onclick="copyText()"> Copiar</button>
                        </div>
                    </div>
                `,
                icon: 'success',
            }).then(function() {
                window.location.href = '{{route("admin.pedidos.index")}}';
            });
            $("#formStore")[0].reset();
            $(".disabled").remove();
        },
        error: function (err) {
            console.log('erro: ' + err);
            Swal.fire({
                title: "Oops!",
                text: err.responseJSON.message,
                icon: "error",
            })  
            // if (err.status == 422) { // when status code is 422, it's a validation issue
            //     console.log(err.responseJSON);
            //     $('#success_message').fadeIn().html(err.responseJSON.message);
            //     // you can loop through the errors object and show it to the user
            //     console.warn(err.responseJSON.errors);
            //     // display errors on each form field
            //     $.each(err.responseJSON.errors, function (i, error) {
            //         var el = $(document).find('[name="' + i + '"]');
            //         el.after($('<span class="error" style="color: red;">' + error[0] +
            //             '</span>'));
            //     });
            // }
        }
    });
});

function initAutocomplete() {
    var input = document.getElementById('bairro');
    var options = {
        types: ['(regions)']
    };
    var autocomplete = new google.maps.places.Autocomplete(input, options);
}
google.maps.event.addDomListener(window, 'load', initAutocomplete);

function franquia() {
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    var franquia = $('#franquia').val();
    var bairro = $('input[name="bairro"]').val();
    
    $.ajax({
        type: "POST",
        url: "{{ route('admin.pedidos.pesquisarFranquia') }}",
        data: JSON.stringify({ franquia: franquia, bairro: bairro, _token: csrfToken }),
        contentType: 'application/json',
        success: function(response) {
            console.log(response.nome_franquia);
            $('input[name="id_franquia"]').val(response.id);
            $('#selecionefranquia').css('display', 'none');
            var viewFranquia = document.querySelector('#viewFranquia');
            viewFranquia.innerHTML = `
            <h5 class="my-3"> ${response.nome_franquia} </h5>`

            var etapa2 = document.querySelector('#etapa2');
            etapa2.classList.add('show');

            $('input[name="tipo_franqueado"]').val(response.tipo_franqueado);

            cardParcial();

        },
        error: function(xhr, status, error) {
            console.error(error);
            swal({
                title: "Oops",
                text: "Não possui franquia que entregue para esse bairro!",
                icon: "error",
            });
        }
    });
}

$("body").on('click', '#buscarFranquia', function (e) {
    e.preventDefault();
    franquia();
});

function mudar(value) {
    var collapseElement = document.getElementById(`${value}`);
    
    var bsCollapse = new bootstrap.Collapse(collapseElement, {
        toggle: true
    });
}

$("body").on('change', '#franquia', function (e) {
    e.preventDefault();
    franquia();
});


var cont = 0;

$("body").on('click', '.adicionaritens', function (e) {
    e.preventDefault();

    var itens = document.querySelector('#itensview');
    var newDiv = document.createElement("div");
    newDiv.classList.add("itens", "mt-2", "row");

    var tipo_franqueado = $('input[name="tipo_franqueado"]').val();

    if(tipo_franqueado == 'trip'){
        newDiv.innerHTML = `
        <h5>Preenche as datas</h5>
        <div class="col-6">
            <div class="row">
                <div class="col-sm-6 col-12 card-periodo">
                    <div>Data de Entrega</div>
                    <span>
                        <div class="single-input">
                            <input type="text" class="form-control data_inicio" name="entrega[data_entrega][]" id="data_inicio-${cont}"
                                value="" data-cont="${cont}">
                        </div>
                    </span>
                </div>
                <div class="col-sm-6 col-12 card-periodo">
                    <div>Data de Devolução</div>
                    <span>
                        <div class="single-input">
                            <input type="text" class="form-control data_termino" name="entrega[data_devolucao][]" id="data_termino-${cont}"
                                value="" data-cont="${cont}">
                        </div>
                    </span>
                </div>
            </div>
            <input type="hidden" name="modalidade" value="aluguel" id="modalidade-${cont}">
            <div class="row my-2" id="viewDisponiveis_${cont}"></div>
        </div>
        <div class="col-4">
            <span class="titulo"></span><br>
            <a href="#" class="btn btn-icon-only btn-danger close" onclick="excluiritens(this)"><i class="fas fa-trash"></i></a>
        </div>
        <hr>
        `;
        
        itens.appendChild(newDiv);

        var startInput = document.getElementById(`data_inicio-${cont}`);
        var endInput = document.getElementById(`data_termino-${cont}`);

        flatpickr(startInput, {
            dateFormat: "Y-m-d",  
            altInput: true,       
            altFormat: "d/m/Y",
            onChange: function(selectedDates, dateStr, instance) {
                if (endInput._flatpickr) {
                    endInput._flatpickr.set('minDate', dateStr);
                }
            }
        });

        flatpickr(endInput, {
            dateFormat: "Y-m-d",  
            altInput: true,       
            altFormat: "d/m/Y",
            onChange: function(selectedDates, dateStr, instance) {
                if (startInput._flatpickr) {
                    startInput._flatpickr.set('maxDate', dateStr);
                }
            }
        });
        
    }
    else if (tipo_franqueado == 'toy'){
        newDiv.innerHTML = `
        <h5>Preenche as datas</h5>
        <div class="col-6">
            <div class="row">
                <div class="col-sm-6 col-12 card-periodo">
                    <div>Data de Entrega</div>
                    <span>
                        <div class="single-input">
                            <input type="date" class="form-control data_inicio_toy" name="entrega[data_entrega][]" id="data_inicio-${cont}"
                                value="" data-cont="${cont}">
                        </div>
                    </span>
                </div>
                <div class="col-sm-6 col-12 card-periodo">
                    <div>Período</div>
                    <select name="periodo" class="form-select periodo" id="periodo-${cont}" data-cont="${cont}">
                        
                    </select>
                    <input type="hidden" name="entrega[data_devolucao][]" id="data_termino-${cont}" value="" data-cont="${cont}">
                </div>
                <div class="col-sm-6 col-12 card-periodo">
                    <div>Modalidade</div>
                    <select name="modalidade[]" class="form-select modalidade" id="modalidade-${cont}" data-cont="${cont}">
                        <option value="aluguel" checked>Aluguel</option>
                        <option value="venda">Venda</option>
                        <option value="variavel">Variável</option>
                    </select>
                </div>
            </div>
            <div class="row my-2" id="viewDisponiveis_${cont}"></div>
        </div>
        <div class="col-4">
            <span class="titulo"></span><br>
            <a href="#" class="btn btn-icon-only btn-danger close" onclick="excluiritens(this)"><i class="fas fa-trash"></i></a>
        </div>
        <hr>
        `;
        
        itens.appendChild(newDiv);

        var franquia = $('input[name="id_franquia"]').val();
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        
        $.ajax({
            type: "POST",
            url: "{{ route('admin.pedidos.periodoFranquias') }}",
            data: JSON.stringify({ franquia: franquia, _token: csrfToken , cont: cont}),
            contentType: 'application/json',
            success: function(response) {
                var cont_novo = cont - 1;
                document.getElementById('periodo-' + cont_novo).innerHTML = response;
            },
            error: function(xhr, status, error) {
                console.error(error);
                swal({
                    title: "Oops",
                    text: "Não possui franquia selecionada!",
                    icon: "error",
                });
            }
        });
    }
    else {
        swal({
            title: "Oops",
            text: "Não possui franquia selecionada!",
            icon: "error",
        });
    }

    cont++;
    
});

function excluiritens(element) {
    element.parentNode.parentNode.remove();
    cont--;
}

function disponivel(cont){
    var franquia = $('input[name="id_franquia"]').val();
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    var data_inicio = $('data_inicio-'+ cont).val();
    var data_termino = $('data_termino-'+ cont).val();
    var modalidade = $('#modalidade-'+ cont).val();
    $.ajax({
        type: "POST",
        url: "{{ route('admin.pedidos.produtosDisponivel') }}",
        data: JSON.stringify({ franquia: franquia, modalidade: modalidade, data_inicio: data_inicio, data_termino: data_termino, _token: csrfToken , cont: cont}),
        contentType: 'application/json',
        success: function(response) {
            $('#viewDisponiveis_' + cont).html(response);
            $('.select2').select2({
        theme: 'bootstrap-5'
    });
        },
        error: function(xhr, status, error) {
            console.error(error);
            swal({
                title: "Oops",
                text: "Não possui franquia que entregue para esse bairro!",
                icon: "error",
            });
        }
    });
}

function valortodos() {
    var itens = document.querySelectorAll('.itens');
    var contadorItens = itens.length;
    var valorTot = 0;

    for (var cont = 0; cont < contadorItens; cont++) {
        var valorElemento = document.querySelector('#valortotal-' + cont);
        var valorTotal = valorElemento ? valorElemento.value : "0";

        console.log('#valortotal-' + cont);
        valorTotal = valorTotal.replace(/\./g, '').replace(/,/g, '.');
        valorTotal = parseFloat(valorTotal);

        if (!isNaN(valorTotal)) {
            valorTot += valorTotal;
            console.log(valorTotal);
        }
    }

    console.log(valorTot);
    var valorTotText = document.querySelector('#textvalorTotal');
    valorTotText.innerHTML = `R$ ${getMoney(valorTot)}`;
}

function qtd_dias(cont) {
    var data_termino = document.getElementById('data_termino-' + cont).value;
    var data_inicio = document.getElementById('data_inicio-' + cont).value;
    var diffDays = 0;

    if (data_inicio && data_termino) {
        var dataInicioDate = new Date(data_inicio);
        var dataTerminoDate = new Date(data_termino);

        var diffTime = dataTerminoDate - dataInicioDate;
        diffDays = diffTime / (1000 * 60 * 60 * 24);
    }
    return diffDays;
}
function updateValor(diffDays, cont) {
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    var produto = $('#produto-' + cont).val();
    console.log(produto);
    $.ajax({
        type: "POST",
        url: "{{ route('admin.pedidos.descontos') }}",
        data: { id: produto,
        daysDifference: diffDays,
        _token: csrfToken },
        success: function(response) {
            console.log('Response: ', response);
            $('#valorunitario-' + cont).val(response.total)
            var valor_unitario = response.total;

            var qtd = document.getElementById('qtd-' + cont);
            var valorTotal = valor_unitario * qtd.value;
            
            $('#valortotal-' + cont).val(getMoney(valorTotal));
           
            $('#inputvalorUni-' + cont).val(getMoney(valorTotal));
            valortodos()

        },
        error: function(xhr, status, error) {
            console.error('AJAX Error: ' + error);
        }
    });
}
function updateValorToy(cont) {
    var periodo = $('#periodo-' + cont).val();
    var produto = $('#produto-' + cont).val();
    var modalidade = $('#modalidade-' + cont).val();

    var url = '{{ route("admin.pedidos.valorPeriodo", ["id" => ":id", "id_produto" => ":id_produto", "modalidade" => ":modalidade"]) }}';
    url = url.replace(':id', periodo).replace(':id_produto', produto).replace(':modalidade', modalidade);
    console.log(url);

    $.get(url, function (data) {
        $('#valorunitario-' + cont).val(data.periodo_valor.valor_periodo);
        var valor_unitario = data.periodo_valor.valor_periodo;

        var qtd = document.getElementById('qtd-' + cont);
        var valorTotal = valor_unitario * qtd.value;
        
        $('#valortotal-' + cont).val(getMoney(valorTotal));
        
        $('#inputvalorUni-' + cont).val(getMoney(valorTotal));
        valortodos();
    });
}

$("body").on('change', '.data_inicio', function (e) { 
    e.preventDefault();
    var cont = $(this).data('cont');
    var data_termino = $('#data_termino-'+ cont).val();

    if(data_termino){
        disponivel(cont);
    }
});

$("body").on('change', '.data_inicio_toy', function (e) { 
    e.preventDefault();
    var cont = $(this).data('cont');
    periodo(cont);
});

$("body").on('change', '.data_termino', function (e) { 
    e.preventDefault();
    var cont = $(this).data('cont'); 
    var data_inicio = $('#data_inicio-'+ cont).val();

    
    console.log(data_inicio);
    if(data_inicio){
        disponivel(cont);
    }
});
$("body").on('change', '.valor_uni', function (e) { 
    e.preventDefault();
    var cont = $(this).data('cont'); 

    var valor_uni = parseFloat($('#inputvalorUni-' + cont).val());
    var qtd = parseFloat($('#qtd-' + cont).val());
    var qtd = $('#qtd-' + cont).val();

    var valortotal = valor_uni * qtd;
    
    $('#valortotal-' + cont).val(getMoney(valortotal));

    valortodos()
});
$("body").on('change', '.modalidade', function (e) { 
    e.preventDefault();
    var cont = $(this).data('cont'); 
    var modalidade = $('#modalidade-'+ cont).val();

    updateValorToy(cont);

    if(modalidade == 'aluguel'){
        $('#periodo-' + cont).css('display', 'block');
    }else if(modalidade == 'variavel'){
        $('#obsContainer-' + cont).css('display', 'block');
    }
    else{
        $('#periodo-' + cont).css('display', 'none');
        $('#obsContainer-' + cont).css('display', 'none');
    }
});
$("body").on('change', '.valor_total', function (e) { 
    e.preventDefault();

    valortodos()
});

$("body").on('change', '.produto', function (e) { 
    e.preventDefault();
    var cont = $(this).data('cont'); 
    var tipo_franqueado = $('input[name="tipo_franqueado"]').val();

    if(tipo_franqueado == 'trip'){
        updateValor(qtd_dias(cont), cont);
    }else{
        updateValorToy(cont);
    }
    

    var produto = $('#produto-' + cont).val();
    var data_termino = $('#data_termino-' + cont).val();
    var data_inicio = $('#data_inicio-' + cont).val();

    filtrarDetalhes(data_termino, data_inicio, produto, cont);
});

function mais(cont) {
    var qtd = document.getElementById('qtd-' + cont);
    qtd.stepUp();

    var valor_uni = parseFloat($('#inputvalorUni-' + cont).val());
    var qtd = parseFloat($('#qtd-' + cont).val());
    var qtd = $('#qtd-' + cont).val();

    console.log()

    var valortotal = valor_uni * qtd;
    
    $('#valortotal-' + cont).val(getMoney(valortotal));
    valortodos()
    // updateValor(qtd_dias(cont), cont);
}

function menos(cont) {
    var qtd = document.getElementById('qtd-' + cont);
    qtd.stepDown();

    var valor_uni = parseFloat($('#inputvalorUni-' + cont).val());
    var qtd = parseFloat($('#qtd-' + cont).val());
    var qtd = $('#qtd-' + cont).val();

    var valortotal = valor_uni * qtd;
    
    $('#valortotal-' + cont).val(getMoney(valortotal));
    // updateValor(qtd_dias(cont), cont);
    valortodos()
}
function copyText() {
    var copyText = document.getElementById("myInput");
    copyText.select();
    copyText.setSelectionRange(0, 99999); 
    navigator.clipboard.writeText(copyText.value).then(function() {
        var successMessage = document.createElement('span');
        successMessage.style.color = 'green';
        successMessage.textContent = 'Texto copiado com sucesso';
        copyText.before(successMessage);
        setTimeout(function() {
            successMessage.remove();
        }, 3000);
    })
}

$("body").on('click', '#cepCalcular', function (e) { 
    e.preventDefault();
    console.log("aaaa");
    var cep = $("#cep").val();
    console.log(cep);
    var cepview = document.querySelector('#cepview');
    var frete = document.querySelector('#frete');
    var franquia = $('#franquia').val();

    $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {

        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        var url = '{{ route("admin.pedidos.endereco", ["id" => ":id"]) }}';
        console.log(franquia);
        url = url.replace(':id', franquia);
        $.ajax({
            type: "POST",
            url: url,
            data: dados,
            headers: {
            'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                var cepview = document.querySelector('#cepview');
                cepview.innerHTML = ``;
                var newDiv = document.createElement("div");
                newDiv.classList.add("col-12", "mt-2", "row");

                newDiv.innerHTML = `
                <p class="mb-0">${dados.logradouro}</p>
                <p class="mb-0">${dados.bairro}, ${dados.localidade} - ${dados.uf}</p>
                <input type="hidden" name="bairro" value="${dados.bairro}">
                <input type="hidden" name="cidade" value="${dados.localidade}">
                <input type="hidden" name="estado" value="${dados.uf}">
                <input type="hidden" name="pais" value="Brasil">
                <input type="hidden" name="endereco" value="${dados.logradouro}">
                    `;

                cepview.appendChild(newDiv);

                frete.style.display = "block";

                if(response.frete_economico == 'nao'){
                    $('#frete_economico').css('display', 'none');
                }
                if (response.frete_expresso == 'nao'){
                    $('#frete_expresso').css('display', 'none');
                }
                if (response.retirada_balcao == 'nao'){
                    $('#retirar_loja').css('display', 'none');
                }

                var economico = document.querySelector('#economico');
                economico.innerHTML = `R$ ${getMoney(response.economico)}`;
                $('input[name="valor_economico"]').val(response.economico);
                var expresso = document.querySelector('#expresso');
                expresso.innerHTML = `R$ ${getMoney(response.expresso)}`;
                $('input[name="valor_expresso"]').val(response.expresso);

            },
            error: function(xhr, status, error) {
                cepview.innerHTML = ``;
                var newDiv = document.createElement("div");
                newDiv.classList.add("col-12", "mt-2", "row");

                newDiv.innerHTML = `Não Entregamos para esse bairro`;

                $('#retirar_loja_view').css('display', 'block');

                cepview.appendChild(newDiv);

                frete.style.display = "none";

                
            }
        });

    });
});

function filtrarDetalhes(data_termino, data_inicio, produto, cont) {
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    var franquia = $('input[name="id_franquia"]').val();
    var modalidade = $('#modalidade-'+ cont).val();
    $.ajax({
        type: "POST",
        url: "{{ route('admin.pedidos.qtdMaxProduto') }}",
        data: { id: produto,
        data_inicio: data_inicio,
        data_termino: data_termino,
        franquia: franquia,
        modalidade: modalidade,
        _token: csrfToken },
        success: function(response) {
            if (response != 0){
                var qtd = $('#qtd-' + cont);

                qtd.attr('max', response);

                $('#textQtd-' + cont).css('display', 'block');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error: ' + error);
        }
    });
} 

$("body").on('change', '.periodo', function (e) { 
    e.preventDefault();
    var cont = $(this).data('cont');
    periodo(cont);
    
});

function periodo(cont){
    var periodo = $('#periodo-' + cont).val();
    var modalidade = $('#modalidade-' + cont).val();
    var produto = '0';

    var url = '{{ route("admin.pedidos.valorPeriodo", ["id" => ":id", "id_produto" => ":id_produto", "modalidade" => ":modalidade"]) }}';
    url = url.replace(':id', periodo).replace(':id_produto', produto).replace(':modalidade', modalidade);
    $.get(url, function (data) {
        console.log(data.periodo.dias);
        var dias = parseInt(data.periodo.dias, 10);
        var data_inicio = $('#data_inicio-'+ cont).val();
        if(data_inicio){
            mudarData(cont, dias);
            disponivel(cont);

            var produto_conf = $('#produto-' + cont).val();

            if (produto_conf){
                updateValorToy(cont);
            }
        }
    });
}

function mudarData(cont, dias){

    var data_inicio = $('#data_inicio-' + cont).val();

    if (data_inicio) {
        var data_inicio_obj = new Date(data_inicio);
        data_inicio_obj.setDate(data_inicio_obj.getDate() + dias);
        var nova_data_termino = data_inicio_obj.toISOString().split('T')[0];
        $('#data_termino-' + cont).val(nova_data_termino);
        
    }
}

</script>
@endsection
