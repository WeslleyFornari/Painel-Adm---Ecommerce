@extends('layouts.app')


<style>
    #card-subdominio .input-group .form-control {
        margin-top: 19px;
        text-align: left;

    }

    #card-subdominio .input-group .input-group-text {
        margin-top: 19px;
        position: absolute;
        right: 0;
        z-index: 10;
        background-color: transparent;
        border-left: none;
    }
</style>

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">

                <div class="card-body">
                    <form action="{{route('admin.franquias.update', $franquia->id)}}" id="formStore" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-12  border-bottom">
                                <h4>Atualizar</h4>
                            </div>

                            <div class="col-6">

                            </div>

                            <div class="row mt-3">
                                @if (Auth::user()->role != 'franqueado')
                                <div class="col-12 col-sm-2">
                                    <label for="">Tipo * </label>

                                    <select class="form-select" name="tipo_franqueado" id="tipo">
                                        <option value="toy" @if($franquia->tipo_franqueado == 'toy') selected @endif>TOY</option>
                                        <option value="trip" @if($franquia->tipo_franqueado == 'trip') selected @endif>TRIP</option>

                                    </select>
                                </div>
                                @else
                                <div class="col-12 col-sm-2">
                                    <label for="">Tipo * </label>

                                    <select class="form-select" name="tipo_franqueado" id="tipo">
                                        @if($franquia->tipo_franqueado == 'toy')
                                        <option value="toy" @if($franquia->tipo_franqueado == 'toy') selected @endif>TOY</option>
                                        @elseif($franquia->tipo_franqueado == 'trip')
                                        <option value="trip" @if($franquia->tipo_franqueado == 'trip') selected @endif>TRIP</option>
                                        @endif
                                    </select>
                                </div>
                                @endif



                                @if (Auth::user()->role != 'franqueado')
                                <div class="col-12 col-sm-5 mb-3" id="card-subdominio">
                                    <label for=""></label>
                                    <div class="input-group" id="subdominio">
                                        <input type="text" class="form-control" name="subdominio" value="{{$franquia->subdominio}}" >
                                        <span class="input-group-text" id="basic-addon2">.facilitoy.com.br</span>
                                    </div>
                                </div>
                                @else
                                <div class="col-12 col-sm-5 mb-3" id="card-subdominio">
                                    <label for=""></label>
                                    <div class="input-group mb-3">

                                        <input type="text" class="form-control" name="subdominio" value="{{$franquia->subdominio}}">
                                        <span class="input-group-text" id="basic-addon2">.facilitoy.com.br</span>
                                    </div>
                                </div>

                                @endif
                                <div class="col-12 col-sm-3">
                                    <label for="">Prefixo Cód. (ex: CAD) *</label>
                                    <input type="text" name="prefix" value="{{$franquia->prefix}}" required class="form-control">
                                </div>
                                <div class="col-12 col-sm-5">
                                    <label for="">Razão Social *</label>
                                    <input type="text" name="nome_franquia" value="{{$franquia->nome_franquia}}" required class="form-control">
                                </div>
                            
                                <div class="col-12 col-sm-4">
                                    <label for="">CNPJ *</label>
                                    <input type="text" name="cnpj" required class="form-control cnpjMask" value="{{$franquia->cnpj}}">
                                </div>
                                <div class="col-12 col-sm-3">
                                    <label for="">Responsável *</label>
                                    <input type="text" name="nome_responsavel" required class="form-control" value="{{$franquia->nome_responsavel}}">
                                </div>
                                <div class="col-12 col-sm-3">
                                    <label for="">Apelido </label>
                                    <input type="text" name="apelido" class="form-control" value="{{$franquia->apelido ?? ''}}">
                                </div>



                            </div>

                            <div class="row mt-3">
                                <div class="col-12 col-sm-3">
                                    <label for="">CPF *</label>
                                    <input type="text" name="cpf" required class="form-control cpfMask" value="{{$franquia->cpf}}">
                                </div>
                                <div class="col-12 col-sm-3">
                                    <label for="">Celular *</label>
                                    <input type="text" name="celular" required class="form-control phoneMask" value="{{$franquia->celular}}">
                                </div>
                                <div class="col-12 col-sm-3">
                                    <label for="">Telefone *</label>
                                    <input type="text" name="telefone" class="form-control phoneMask" value="{{$franquia->telefone}}">
                                </div>

                                <div class="col-12 col-sm-3">
                                    <label for="">CEP *</label>
                                    <div class="input-group ">
                                        <input type="text" class="form-control cepMask border-radius-bottom-end-0" name="cep" id="buscaCep" value="{{$franquia->cep}}" required>
                                        <button class="btn btn-outline-primary mb-0" type="button"> <i class="fa fa-search"></i></button>
                                    </div>
                                </div>

                            </div>

                            <div class="row mt-3">
                                <div class="col-12 col-sm-4">
                                    <label for="">Endereço *</label>
                                    <input type="text" name="endereco" required class="form-control" value="{{$franquia->endereco}}">
                                </div>

                                <div class="col-6 col-sm-2">
                                    <label for="">Número *</label>
                                    <input type="text" name="numero" required class="form-control" value="{{$franquia->numero}}">
                                </div>
                                <div class="col-3">
                                    <label for="">Complemento</label>
                                    <input type="text" name="complemento" class="form-control" value="{{$franquia->complemento}}">
                                </div>
                                <div class="col-3">
                                    <label for="">Bairro * </label>
                                    <input type="text" name="bairro" required class="form-control" value="{{$franquia->bairro}}">
                                </div>


                            </div>

                            <div class="row mt-3">
                                <div class="col-3">
                                    <label for="">Cidade *</label>
                                    <input type="text" name="cidade" required class="form-control" value="{{$franquia->cidade}}">
                                </div>
                                <div class="col-2">
                                    <label for="">Estado * </label>
                                    <input type="text" name="estado" required class="form-control" value="{{$franquia->estado}}">
                                </div>
                                <div class="col-3">
                                    <label for="">País * </label>
                                    <input type="text" name="pais" required class="form-control" value="{{$franquia->pais}}">
                                </div>
                                <div class="col-4">
                                <label class="text-danger" for="">Email Principal * </label>
                                <input type="email" name="email" required class="form-control"  value="{{$franquia->email ?? ''}}">
                            </div>

                            </div>

                            <div class="row mt-3">
                                <label for="">Retirada no Balcão</label>
                                <fieldset>
                                    <div>
                                        <input type="radio" id="sim_balcao" name="retirada_balcao" value="sim" @if($franquia->retirada_balcao == 'sim') checked @endif/>
                                        <label for="sim_balcao">Sim</label>
                                    </div>
                                    <div>
                                        <input type="radio" id="nao_balcao" name="retirada_balcao" value="nao" @if($franquia->retirada_balcao == 'nao') checked @endif/>
                                        <label for="nao_balcao">Não</label>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="row mt-3">
                                <label for="">Frete Econômico</label>
                                <fieldset>
                                    <div>
                                        <input type="radio" id="sim_economico" name="frete_economico" value="sim" @if($franquia->frete_economico == 'sim') checked @endif/>
                                        <label for="sim_economico">Sim</label>
                                    </div>
                                    <div>
                                        <input type="radio" id="nao_economico" name="frete_economico" value="nao" @if($franquia->frete_economico == 'nao') checked @endif/>
                                        <label for="nao_economico">Não</label>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="row mt-3">
                                <label for="">Frete Expresso</label>
                                <fieldset>
                                    <div>
                                        <input type="radio" id="sim_expresso" name="frete_expresso" value="sim" @if($franquia->frete_expresso == 'sim') checked @endif/>
                                        <label for="sim_expresso">Sim</label>
                                    </div>
                                    <div>
                                        <input type="radio" id="nao_expresso" name="frete_expresso" value="nao" @if($franquia->frete_expresso == 'nao') checked @endif/>
                                        <label for="nao_expresso">Não</label>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="" id="periodo" @if($franquia->tipo_franqueado != 'toy') style="display: none" @endif>
                                <hr>

                                @if(Auth::user()->role == 'admin' || Auth::user()->role == 'master')
                                <h5>Períodos que possui de aluguel</h5>

                                @foreach ($franquia->periodos as $periodo)
                                <div class="col-12 mt-2 row">
                                    <div class="col-2 mt-2">
                                        <span class="titulo"> Qtd Dias: </span>
                                        <input type="text" name="periodos[dias][]" value="{{ $periodo->dias }}" class="form-control">
                                    </div>
                                    <div class="col-2">
                                        <span class="titulo"></span><br>
                                        <a href="#" class="btn btn-icon-only btn-danger close" onclick="excluirperiodos(this)"><i class="fas fa-trash"></i></a>
                                    </div>
                                </div>
                                @endforeach
                                <div class="" id="periodosview"></div>
                                <a href="" class="btn btn-sm btn-primary adicionarperiodos" style="font-size: 15px"><i class="fas fa-plus-circle" style="font-size: 15px"></i> Adicionar Período</a>
                                @endif

                                <hr>
                                <h5>Redes Sociais</h5>
                                <div class="row mb-3">
                                    <div class="col-4">
                                        <label for="">Instagram</label>
                                        <input type="text" name="instagram" class="form-control" value="{{$franquia->instagram}}">
                                    </div>
                                    <div class="col-4">
                                        <label for="">Facebook</label>
                                        <input type="text" name="facebook" class="form-control" value="{{$franquia->facebook}}">
                                    </div>
                                    <div class="col-4">
                                        <label for="">Youtube</label>
                                        <input type="text" name="youtube" class="form-control" value="{{$franquia->youtube}}">
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="row mt-3">
                                <!-- <div class="col-3">
                                <label for="">Id</label>
                                <input type="text" name="cod_franqueado" class="form-control" value="{{$franquia->cod_franqueado}}">
                            </div>

                            <div class="col-2">
                                <label for="">Percentual da Unidade (Pedido realizado pelo Site) </label>
                                <input type="text" name="percentual_automatico_franqueado" class="form-control" value="{{$franquia->percentual_automatico_franqueado}}">
                            </div>
                            <div class="col-2">
                                <label for="">Percentual da Unidade (Pedido realizado Manualmente) </label>
                                <input type="text" name="percentual_manual_franqueado" class="form-control" value="{{$franquia->percentual_manual_franqueado}}">
                            </div> -->
                                <div class="col-8">
                                    <label for="">Chave Secreta </label>
                                    <input type="text" name="apiKey" class="form-control" value="{{$franquia->apiKey}}">
                                </div>
                                <div class="col-4">
                                    <label for="">Gateway </label><br>
                                    <select class="form-select" name="gateway" id="">
                                        <option value="pagarme" {{ $franquia->gateway == 'pagarme' ? 'selected' : '' }}>Pagar.me</option>
                                        <option value="asaas" {{ $franquia->gateway == 'asaas' ? 'selected' : '' }}>Asaas</option>
                                    </select>
                                </div>

                                <hr>

                                <div class="row mt-4 border-top pt-4">
                                    <h5>Integração Banco Inter</h5>

                                    <div class="col-12 col-sm-6 mb-3">
                                        <label for="">Chave Pública Inter</label>
                                        <input type="text" name="chave_publica_inter" class="form-control" value="{{ $franquia->chave_publica_inter ?? '' }}">
                                    </div>

                                    <div class="col-12 col-sm-6 mb-3">
                                        <label for="">Chave Secreta Inter</label>
                                        <input type="text" name="chave_secreta_inter" class="form-control" value="{{ $franquia->chave_secreta_inter ?? '' }}">
                                    </div>

                                    <div class="col-12 col-sm-4 mb-3">
                                        <label for="">Arquivo de Certificado (.crt)</label>
                                        <input type="file" name="certificado_inter" class="form-control" accept=".crt">
                                        @if(isset($franquia->certificado_inter) && !empty($franquia->certificado_inter))
                                        <small class="text-success">Certificado já enviado</small> <br>
                                        @endif
                                        <small class="text-muted">Faça upload do arquivo de certificado fornecido pelo Banco Inter</small>
                                    </div>

                                    <div class="col-12 col-sm-4 mb-3">
                                        <label for="">Arquivo de Chave Privada (.key)</label>
                                        <input type="file" name="chave_inter" class="form-control" accept=".key">
                                        @if(isset($franquia->chave_inter) && !empty($franquia->chave_inter))
                                        <small class="text-success">Chave privada já enviada</small> <br>
                                        @endif
                                        <small class="text-muted">Faça upload do arquivo de chave privada fornecido pelo Banco Inter</small>
                                    </div>

                                    <div class="col-12 col-sm-4 mb-3">
                                        <label for="">Arquivo de Certificado CA (.crt)</label>
                                        <input type="file" name="webhook_inter" class="form-control" accept=".crt">
                                        @if(isset($franquia->webhook_inter) && !empty($franquia->webhook_inter))
                                        <small class="text-success">Certificado CA já enviado</small> <br>
                                        @endif
                                        <small class="text-muted">Faça upload do arquivo CA fornecido pelo Banco Inter</small>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label for="">Chave Pix</label>
                                        <input type="text" name="chave_pix_inter" class="form-control" value="{{ $franquia->chave_pix_inter ?? '' }}" placeholder="Chave PIX">
                                    </div>
                                </div>

                            </div>

                            <div class="row mt-3 border-top pt-5 mt-5">
                                @if (Auth::user()->role != 'franqueado')
                                <div class="col">
                                    <a href="{{route('admin.franquias.index')}}" class="btn btn-primary">Voltar</a>
                                </div>
                                @endif
                                <div class="col text-end">
                                    <button class="btn btn-success" type="submit">Atualizar</button>
                                </div>
                            </div>


                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')
<script>
    verificarTipo();

    $("body").on("change", "#tipo", function() {
        verificarTipo();
    });

    function verificarTipo() {

        var tipo = $("#tipo").val();

        if (tipo == 'trip') {
            $("#periodo").css('display', 'none');
            $("#card-subdominio").css('display', 'none');
        } else {
            $("#periodo").css('display', 'block');
            $("#card-subdominio").css('display', 'block');
        }
    }


    $("#formStore").submit(function(e) {
        e.preventDefault();
        $("span.error").remove();

        var role = '{{Auth::user()->role}}'
        var formData = new FormData(this);


        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                console.log(data);

                var apiKey = $('input[name="apiKey"]').val();

                switch (apiKey) {
                    case "":
                    case null:
                    case undefined:
                        swal({
                            title: "Atenção",
                            text: "Sem a chave secreta do PagarMe, não é possível fazer locação e pagamento.",
                            icon: "warning",
                            buttons: {
                                cancel: "Cancelar",
                                continue: {
                                    text: "Continuar sem Chave Secreta",
                                    value: "continue",
                                }
                            },
                        }).then((value) => {
                            if (value === "continue") {
                                swal({
                                    title: "Parabéns",
                                    text: "Alteração realizado com sucesso!",
                                    icon: "success",
                                }).then(function() {
                                    if (role == 'franqueado') {
                                        location.reload();
                                    } else {
                                        window.location.href = '{{route("admin.franquias.index")}}'
                                    }

                                });
                            }
                        });
                        break;
                    default:
                        swal({
                            title: "Parabéns",
                            text: "Alteração realizado com sucesso!",
                            icon: "success",
                        }).then(function() {
                            if (role == 'franqueado') {
                                location.reload();
                            } else {
                                window.location.href = '{{route("admin.franquias.index")}}'
                            }
                        });
                }
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

    $("body").on('click', '.adicionarperiodos', function(e) {
        e.preventDefault();
        var periodos = document.querySelector('#periodosview');
        var newDiv = document.createElement("div");
        newDiv.classList.add("col-12", "mt-2", "row");
        newDiv.innerHTML = `
        <div class="col-2">
            <span class="titulo"> Qtd Dias: </span>
            <input type="text" name="periodos[dias][]" value="" class="form-control">
        </div>
        <div class="col-2">
            <span class="titulo"></span><br>
            <a href="#" class="btn btn-icon-only btn-danger close" onclick="excluirperiodos(this)"><i class="fas fa-trash"></i></a>
        </div>
    `;
        periodos.appendChild(newDiv);
    });

    function excluirperiodos(element) {
        element.parentNode.parentNode.remove();
    }
</script>
@endsection