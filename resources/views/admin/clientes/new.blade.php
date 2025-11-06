@extends('layouts.app')


@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">

                <div class="card-body">

                    <ul class="nav nav-tabs" id="myTab" role="tablist">

                        <li class="nav-item" role="presentation">
                            <button class="nav-link active btn-home" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Home</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            @if(isset($cliente))
                            <button class="nav-link btn-profile" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Dados Complementares</button>
                            @else
                            <button class="nav-link disabled btn-profile" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Dados Complementares</button>
                            @endif
                        </li>
                        <li class="nav-item" role="presentation">
                            @if(isset($cliente))
                            <button class="nav-link btn-endereco" id="endereco-tab" data-bs-toggle="tab" data-bs-target="#endereco" type="button" role="tab" aria-controls="endereco" aria-selected="false">Endereços</button>
                            @else
                            <button class="nav-link disabled btn-endereco" id="endereco-tab" data-bs-toggle="tab" data-bs-target="#endereco" type="button" role="tab" aria-controls="endereco" aria-selected="false">Endereços</button>
                            @endif
                        </li>

                    </ul>

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active home" id="home" role="tabpanel" aria-labelledby="home-tab"> @include('admin.clientes._inicio')</div>
                        <div class="tab-pane fade profile" id="profile" role="tabpanel" aria-labelledby="profile-tab">@include('admin.clientes._dados_complementares')</div>
                        <div class="tab-pane fade endereco" id="endereco" role="tabpanel" aria-labelledby="endereco-tab">@include('admin.clientes._enderecos')</div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" role="dialog" aria-hidden="true" id="modalEndereco">

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Novo Endereço</h5>
                <button type="button" class="btn btn-sm close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" id="conteudo-login">
                <form action="" id="formEndereco" method="POST" enctype="multipart/form-data" class="d-flex flex-column align-items-center">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12 col-12">
                            <label for="" class="title-cadastro">Apelido*</label>
                            <input type="text" class="form-control" name="apelido">
                        </div>
                        <div class="col-12">
                            <!-- <input type="text" class="form-control" name="cep" id="cep"> -->
                            <label for="" class="title-cadastro">CEP*</label>
                            <div class="input-group ">
                                <input type="text" class="form-control cepMask border-radius-bottom-end-0 cep-input" name="cep" id="buscaCep" required>
                                <button class="btn botaocep mb-0" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-sm-12 col-12">
                            <label for="" class="title-cadastro">Endereço*</label>
                            <input type="text" class="form-control" name="endereco">
                        </div>
                        <div class="col-sm-9 col-12">
                            <label for="" class="title-cadastro">Bairro*</label>
                            <input type="text" class="form-control" name="bairro">
                        </div>
                        <div class="col-sm-3 col-12">
                            <label for="" class="title-cadastro">Número*</label>
                            <input type="text" class="form-control" name="numero">
                        </div>
                        <div class="col-sm-5 col-12">
                            <label for="" class="title-cadastro">Cidade*</label>
                            <input type="text" class="form-control" name="cidade">
                        </div>
                        <div class="col-sm-3 col-12">
                            <label for="" class="title-cadastro">Estado*</label>
                            <input type="text" class="form-control" name="estado">
                        </div>
                        <div class="col-sm-4 col-12">
                            <label for="" class="title-cadastro">País*</label>
                            <input type="text" class="form-control" name="pais">
                        </div>
                        <div class="col-sm-6 col-12">
                            <label for="" class="title-cadastro">Complemento</label>
                            <input type="text" class="form-control" name="complemento">
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
            </form>
        </div>
    </div>
</div>


@endsection

@section('scripts')

<script>
    function validarCNPJ(cnpj) {
        cnpj = cnpj.replace(/[^\d]+/g, '');

        if (cnpj == '') return true;
        if (cnpj.length != 14) return false;

        if (/^(\d)\1{13}$/.test(cnpj)) return false;

        let tamanho = cnpj.length - 2;
        let numeros = cnpj.substring(0, tamanho);
        let digitos = cnpj.substring(tamanho);
        let soma = 0;
        let pos = tamanho - 7;

        for (let i = tamanho; i >= 1; i--) {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2) pos = 9;
        }

        let resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(0)) return false;

        tamanho = tamanho + 1;
        numeros = cnpj.substring(0, tamanho);
        soma = 0;
        pos = tamanho - 7;

        for (let i = tamanho; i >= 1; i--) {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2) pos = 9;
        }

        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(1)) return false;

        return true;
    }

    $('body').on('blur', '#cnpj', function() {
        const cnpj = $(this).val();
        const cnpjError = $('#cnpj-error');
        const submitButton = $('button[type="submit"]');

        if (validarCNPJ(cnpj)) {
            cnpjError.hide();
            submitButton.removeAttr('disabled');
        } else {
            cnpjError.show();
            submitButton.attr('disabled', true);

        }
    });

    window.onload = function() {
        var cliente = "{{isset($cliente)}}";

        if (cliente) {
            dados();
        }
    };
    // STORE
    $(".formStoreclientes").submit(function(e) {

        e.preventDefault();
        $("span.error").hide()

        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success: function(data) {
                // console.log(data);

                // swal({
                //     title: "Parábens",
                //     text: "Cadastro realizado com sucesso!.",
                //     icon: "success",
                // }).then(function() {

                //     var id_user = data.id_user;
                //     window.location.href = '/admin/clientes/edit/' + id_user;
                //     $('#profile-tab').click();

                // });

                var id_user = data.id_user;
                window.location.href = '{{route("admin.clientes.edit")}}/' + id_user;
                $('#profile-tab').click();
            },

            error: function(err) {
                console.log(err);

                if (err.status == 422) {
                    console.log(err.responseJSON);
                    $('#success_message').fadeIn().html(err.responseJSON.message);

                    console.warn(err.responseJSON.errors);

                    $.each(err.responseJSON.errors, function(i, error) {
                        var el = $(document).find('[name="' + i + '"]');
                        el.after($('<span class="error" style="color: red; font-size:12px; margin-left:10px; border: none; font-weight: bold;">' + error[0] +
                            '</span>'));
                    });
                }
            }
        })
    })
    $("#formEndereco").submit(function(e) {

        e.preventDefault();
        $("span.error").remove()

        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success: function(data) {
                var url = '{{ route("admin.clientes.deleteEndereco", ["id" => ":id"]) }}';
                url = url.replace(':id', data.id);
                var enderecos = document.querySelector('#enderecosview');
                enderecos.innerHTML = `
                    <div class="row">
                        <div class="col-6">
                            <div class="d-flex m-3 ms-0">
                                <div class="me-2" style="width: 40px; height: 40px; border-radius: 50%; background: #fff; display: flex; align-items: center; justify-content: center;">
                                    <i class="fad fa-map-marked-alt"></i>
                                </div>
                                <label class="form-check-label" for="endereco">
                                    ${data.apelido}<br>
                                    ${data.endereco}, n° ${data.numero} - ${data.bairro}<br>
                                    CEP: ${data.cep} - ${data.cidade}, ${data.estado}
                                </label>
                            </div>
                        </div>
                        <div class="align-content-center col-2 row">
                            <a href="${url}" class="btn btn-sm btn-icon-only btn-danger btn-destroyEndereco">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </div>
                `;

                $("#formEndereco")[0].reset();
                $('#modalEndereco').modal('hide');
            },

            error: function(err) {
                console.log(err);

                if (err.status == 422) {
                    console.log(err.responseJSON);
                    $('#success_message').fadeIn().html(err.responseJSON.message);

                    console.warn(err.responseJSON.errors);

                    $.each(err.responseJSON.errors, function(i, error) {
                        var el = $(document).find('[name="' + i + '"]');
                        el.after($('<span class="error" style="color: red; font-size:12px; margin-left:10px; border: none; font-weight: bold;">' + error[0] +
                            '</span>'));
                    });
                }
            }
        })
    })

    // STORE DAOS CLINETES
    $("#formStoreDadosClientes").submit(function(e) {

        e.preventDefault();
        $("span.error").hide()

        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success: function(data) {
                console.log(data);

                swal({
                    title: "Parábens",
                    text: "Cadastro realizado com sucesso!.",
                    icon: "success",
                }).then(function() {

                    window.location.href = "{{route('admin.clientes.index')}}"

                });
            },

            error: function(err) {
                console.log(err);

                if (err.status == 422) {
                    console.log(err.responseJSON);
                    $('#success_message').fadeIn().html(err.responseJSON.message);

                    console.warn(err.responseJSON.errors);

                    $.each(err.responseJSON.errors, function(i, error) {
                        var el = $(document).find('[name="' + i + '"]');
                        el.after($('<span class="error" style="color: red; font-size:12px; margin-left:10px; border: none; font-weight: bold;">' + error[0] +
                            '</span>'));
                    });
                }
            }
        })
    })

    function dados() {
        var home = document.querySelector('.home');
        var profile = document.querySelector('.profile');
        var btn_home = document.querySelector('.btn-home');
        var btn_profile = document.querySelector('.btn-profile');
        profile.classList.add('active', 'show');
        home.classList.remove('show', 'active');
        btn_profile.classList.add('active');
        btn_home.classList.remove('active');
    }

    $("body").on('click', '.abrirEndereco', function(e) {
        e.preventDefault();

        var usuario = $(this).data('usuario');

        var url = '{{ route("admin.clientes.enderecos", ["idUsuario" => ":idUsuario"]) }}';
        url = url.replace(':idUsuario', usuario);

        $('#modalEndereco').modal('show');
        $("#formEndereco").attr('action', url);
    });

    $("body").on('click', '.btn-destroyEndereco', function(e) {
        var url = $(this).attr('href');
        e.preventDefault();

        $(this).closest('tr').addClass("remove-row");
        $(this).closest('.row').addClass("remove-row");

        swal({
            title: "Você tem certeza?",
            text: "Você removerá permanentemente este item",
            icon: "warning",
            dangerMode: true,
            buttons: {
                cancel: {
                    text: "Cancelar",
                    value: null,
                    visible: true,
                    className: "",
                    closeModal: true,
                },
                confirm: {
                    text: "OK",
                    value: true,
                    visible: true,
                    className: "",
                    closeModal: true
                }
            }
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        if (willDelete) {
                            $(".remove-row").remove();
                            swal("Sucesso!", "Item removido com sucesso", "success");
                        }
                    },
                    error: function(err) {
                        var erro = err.responseJSON;
                        swal("Atenção!", erro.error, "error");
                    }
                }).then(function() {
                    location.reload();
                });
            }
        });
    });
</script>


@endsection