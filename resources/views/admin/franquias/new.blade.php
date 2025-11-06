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

                    <form action="{{route('admin.franquias.store')}}" id="formStore" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-4 border-bottom">
                            <div class="col-6">
                                <h4>Cadastro</h4>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-12 col-sm-2 mb-3">
                                <label for="">Tipo * </label>

                                <select class="form-select" name="tipo_franqueado" required id="tipo">
                                    <option value="">Selecione</option>
                                    <option value="trip" selected>TRIP</option>
                                    <option value="toy">TOY</option>
                                </select>
                            </div>

                            <div class="col-12 col-sm-5 mb-3" id="card-subdominio" style="display:none">
                                <label for=""></label>
                                <div class="input-group" id="subdominio">
                                    <input type="text" class="form-control" name="subdominio" placeholder="Subdominio">
                                    <span class="input-group-text" id="basic-addon2">.facilitoy.com.br</span>
                                </div>
                            </div>

                            <div class="col-12 col-sm-3">
                                <label for="">Prefixo Cód. (ex: CAD) *</label>
                                <input type="text" name="prefix" required class="form-control">
                            </div>
                            <div class="col-12 col-sm-5">
                                <label for="">Razão Social *</label>
                                <input type="text" name="nome_franquia" required class="form-control">
                            </div>
                            <div class="col-12 col-sm-4">
                                <label for="">CNPJ *</label>
                                <input type="text" name="cnpj" required class="form-control cnpjMask">
                            </div>
                            <div class="col-12 col-sm-6">
                                <label for="">Responsável *</label>
                                <input type="text" name="nome_responsavel" required class="form-control">
                            </div>
                            <div class="col-12 col-sm-6">
                                <label for="">Apelido </label>
                                <input type="text" name="apelido" required class="form-control">
                            </div>



                        </div>

                        <div class="row mt-3">
                            <div class="col-12 col-sm-3">
                                <label for="">CPF *</label>
                                <input type="text" name="cpf" required class="form-control cpfMask" id="cpf">
                            </div>
                            <div class="col-12 col-sm-3">
                                <label for="">Celular *</label>
                                <input type="text" name="celular" required class="form-control phoneMask">
                            </div>
                            <div class="col-12 col-sm-3">
                                <label for="">Telefone*</label>
                                <input type="text" name="telefone" class="form-control phoneMask">
                            </div>

                            <div class="col-12 col-sm-3">
                                <label for="">CEP *</label>
                                <div class="input-group ">
                                    <input type="text" class="form-control cepMask border-radius-bottom-end-0" name="cep" id="buscaCep" required>
                                    <button class="btn btn-outline-primary mb-0" type="button"> <i class="fa fa-search"></i></button>
                                </div>
                            </div>

                        </div>

                        <div class="row mt-3">
                            <div class="col-12 col-sm-4">
                                <label for="">Endereço *</label>
                                <input type="text" name="endereco" required class="form-control">
                            </div>

                            <div class="col-6 col-sm-2">
                                <label for="">Número *</label>
                                <input type="text" name="numero" required class="form-control">
                            </div>
                            <div class="col-3">
                                <label for="">Complemento</label>
                                <input type="text" name="complemento" class="form-control">
                            </div>
                            <div class="col-3">
                                <label for="">Bairro * </label>
                                <input type="text" name="bairro" required class="form-control">
                            </div>


                        </div>

                        <div class="row mt-3">
                            <div class="col-3">
                                <label for="">Cidade *</label>
                                <input type="text" name="cidade" required class="form-control">
                            </div>
                            <div class="col-2">
                                <label for="">Estado * </label>
                                <input type="text" name="estado" required class="form-control">
                            </div>
                            <div class="col-3">
                                <label for="">País * </label>
                                <input type="text" name="pais" required class="form-control">
                            </div>

                            <div class="col-4">
                                <label class="text-danger" for="">Email Principal * </label>
                                <input type="email" name="email" required class="form-control">
                            </div>

                        </div>
                        <div class="row mt-3">
                            <label for="">Retirada no Balcão</label>
                            <fieldset>
                                <div>
                                    <input type="radio" id="sim_balcao" checked name="retirada_balcao" value="sim" />
                                    <label for="sim_balcao">Sim</label>
                                </div>
                                <div>
                                    <input type="radio" id="nao_balcao" name="retirada_balcao" value="nao" />
                                    <label for="nao_balcao">Não</label>
                                </div>
                            </fieldset>
                        </div>
                        <div class="row mt-3">
                            <label for="">Frete Econônico</label>
                            <fieldset>
                                <div>
                                    <input type="radio" id="sim_economico" checked name="frete_economico" value="sim" />
                                    <label for="sim_economico">Sim</label>
                                </div>
                                <div>
                                    <input type="radio" id="nao_economico" name="frete_economico" value="nao" />
                                    <label for="nao_economico">Não</label>
                                </div>
                            </fieldset>
                        </div>
                        <div class="row mt-3">
                            <label for="">Frete Expresso</label>
                            <fieldset>
                                <div>
                                    <input type="radio" id="sim_expresso" checked name="frete_expresso" value="sim" />
                                    <label for="sim_expresso">Sim</label>
                                </div>
                                <div>
                                    <input type="radio" id="nao_expresso" name="frete_expresso" value="nao" />
                                    <label for="nao_expresso">Não</label>
                                </div>
                            </fieldset>
                        </div>
                        <div class="" id="periodo" style="display: none">
                            <hr>

                            @if(Auth::user()->role == 'admin' || Auth::user()->role == 'master')
                            <h5>Períodos que possui de aluguel</h5>
                            <div class="" id="periodosview"></div>
                            <a href="" class="btn btn-sm btn-primary adicionarperiodos" style="font-size: 15px"><i class="fas fa-plus-circle" style="font-size: 15px"></i> Adicionar Período</a>
                            @endif
                            <hr>
                            <h5>Redes Sociais</h5>
                            <div class="row">
                                <div class="col-4">
                                    <label for="">Instagram</label>
                                    <input type="text" name="instagram" class="form-control">
                                </div>
                                <div class="col-4">
                                    <label for="">Facebook</label>
                                    <input type="text" name="facebook" class="form-control">
                                </div>
                                <div class="col-4">
                                    <label for="">Youtube</label>
                                    <input type="text" name="youtube" class="form-control">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row mt-3">
                            <!-- <div class="col-3">
                                <label for="">Id</label>
                                <input type="text" name="cod_franqueado" class="form-control">
                            </div>
                            <div class="col-2">
                                <label for="">Percentual da Unidade (Pedido realizado pelo Site) </label>
                                <input type="text" name="percentual_automatico_franqueado" class="form-control">
                            </div>
                            <div class="col-2">
                                <label for="">Percentual da Unidade (Pedido realizado Manualmente) </label>
                                <input type="text" name="percentual_manual_franqueado" class="form-control">
                            </div> -->
                            <div class="col-8">
                                <label for="">Chave Secreta </label>
                                <input type="text" name="apiKey" class="form-control">
                            </div>
                            <div class="col-4">
                                <label for="">Gateway </label><br>
                                <select class="form-select" name="gateway" id="">
                                    <option id="" value="pagarme">Pagar.me</option>
                                    <option id="" value="asaas">Asaas</option>
                                </select>
                            </div>


                        </div>

                        <hr>
                        <div class="row mt-4 border-top pt-4">
                            <h5>Integração Banco Inter</h5>

                            <div class="col-12 col-sm-6 mb-3">
                                <label for="">Chave Pública Inter</label>
                                <input type="text" name="chave_publica_inter" class="form-control">
                            </div>

                            <div class="col-12 col-sm-6 mb-3">
                                <label for="">Chave Secreta Inter</label>
                                <input type="text" name="chave_secreta_inter" class="form-control">
                            </div>

                            <div class="col-12 col-sm-4 mb-3">
                                <label for="">Arquivo de Certificado (.crt)</label>
                                <input type="file" name="certificado_inter" class="form-control" accept=".crt">
                                <small class="text-muted">Faça upload do arquivo de certificado fornecido pelo Banco Inter</small>
                            </div>

                            <div class="col-12 col-sm-4 mb-3">
                                <label for="">Arquivo de Chave Privada (.key)</label>
                                <input type="file" name="chave_inter" class="form-control" accept=".key">
                                <small class="text-muted">Faça upload do arquivo de chave privada fornecido pelo Banco Inter</small>
                            </div>

                            <div class="col-12 col-sm-4 mb-3">
                                <label for="">Arquivo de Certificado CA (.crt)</label>
                                <input type="file" name="webhook_inter" class="form-control" accept=".crt">
                                <small class="text-muted">Faça upload do arquivo CA fornecido pelo Banco Inter</small>
                            </div>

                            <div class="col-12 mb-3">
                                <label for="">Chave Pix</label>
                                <input type="text" name="chave_pix_inter" class="form-control" placeholder="Chave PIX">
                            </div>
                        </div>

                        <div class="row mt-3 border-top pt-5 mt-5">
                            <div class="col">
                                <a href="{{route('admin.franquias.index')}}" class="btn btn-primary">Voltar</a>
                            </div>
                            <div class="col text-end">
                                <button class="btn btn-success" type="submit">Salvar</button>
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

    // STORE
    $("#formStore").submit(function(e) {
        e.preventDefault();
        $("span.error").remove();
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
                                    text: "Cadastro realizado com sucesso!",
                                    icon: "success",
                                }).then(function() {
                                    location.reload();
                                });
                            }
                        });
                        break;
                    default:
                        swal({
                            title: "Parabéns",
                            text: "Cadastro realizado com sucesso!",
                            icon: "success",
                        }).then(function() {
                            location.reload();
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
        <div class="col-2 mt-1">
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