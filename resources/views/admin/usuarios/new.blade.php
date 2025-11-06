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
                @if(isset($usuario))
                        <button class="nav-link btn-profile" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Dados Complementares</button>
                    @else 
                        <button class="nav-link disabled btn-profile" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Dados Complementares</button>
                   @endif
                    </li>
            
                </ul>

                <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active home" id="home" role="tabpanel" aria-labelledby="home-tab"> @include('admin.usuarios._inicio')</div>
                <div class="tab-pane fade profile" id="profile" role="tabpanel" aria-labelledby="profile-tab">@include('admin.usuarios._dados_complementares')</div>
                
                </div>
                
               </div>
            </div>
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

    $('body').on('blur', '#cnpj', function () {
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

//
// RETIREI A FUNÇÃO, NÃO VI SENTIDO EM CARREGAR O USUARIO NA SEGUNDA ABA ATIVA

//  window.onload = function () {
//     var usuario = "{{isset($usuario)}}";

//     if (usuario){
//         dados();
//     }
// };
// STORE
      $(".formStoreUsuarios").submit(function (e) {

        e.preventDefault();
        $("span.error").hide()

        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: $(this).serialize(),

            success: function (data) {
                // console.log(data);
                if (data.status == 'editado'){
                    swal({
                        title: "Parábens",
                        text: "Edição realizado com sucesso!.",
                        icon: "success",
                    }).then(function() {

                        var id_user = data.id_user;
                        window.location.href = "{{route('admin.usuarios.edit')}}/" + id_user;
                        $('#profile-tab').click();

                    });
                }
                else{
                    swal({
                    title: "Parábens",
                    text: "Cadastro realizado com sucesso!.",
                    icon: "success",
                    }).then(function() {

                        var id_user = data.id_user;
                        window.location.href = "{{route('admin.usuarios.edit')}}/" + id_user;
                        $('#profile-tab').click();

                    });
                }

                // var id_user = data.id_user;
                // window.location.href = '/admin/usuarios/edit/' + id_user;
                // $('#profile-tab').click();
            },

            error: function (err) {
                console.log(err);

                if (err.status == 422) { 
                    console.log(err.responseJSON);
                    $('#success_message').fadeIn().html(err.responseJSON.message);
                   
                    console.warn(err.responseJSON.errors);
                   
                    $.each(err.responseJSON.errors, function (i, error) {
                        var el = $(document).find('[name="' + i + '"]');
                        el.after($('<span class="error" style="color: red; font-size:12px; margin-left:10px; border: none; font-weight: bold;">' + error[0] +
                            '</span>'));
                    });
                }
            }
        })
    })

// STORE DAOS CLINETES
    $("#formStoreDadosClientes").submit(function (e) {

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
                    text: "Dados Complementares Cadastrado com sucesso!.",
                    icon: "success",
                }).then(function() {

                    window.location.href = "{{route('admin.usuarios.index')}}"

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
                        el.after($('<span class="error" style="color: red; font-size:12px; margin-left:10px; border: none; font-weight: bold;">' + error[0] +
                            '</span>'));
                    });
                }
            }
        })
})

function dados(){
    var home = document.querySelector('.home');
    var profile = document.querySelector('.profile');
    var btn_home = document.querySelector('.btn-home');
    var btn_profile = document.querySelector('.btn-profile');
    profile.classList.add('active', 'show');
    home.classList.remove('show', 'active');
    btn_profile.classList.add('active');
    btn_home.classList.remove('active');
}

$("body").on('change', '.role', function (e) {
    e.preventDefault();
    var role = this.value;
    if (role === 'franqueado') {
        $("#franquia").css("display", "block");
    }
    else{
        $("#franquia").css("display", "none");
    }
});

</script>


@endsection