<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- TOKEN -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>
  {{config('APP_NAME')}}
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="{{asset('assets/css/nucleo-icons.css')}}" rel="stylesheet" />
  <link href="{{asset('assets/css/nucleo-svg.css')}}" rel="stylesheet" />

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v5.15.4/css/all.css">  
  
  <!-- Estilos CSS / TOGGLE-->
  <link rel="stylesheet" href="{{asset('css/estilos.css')}}">
  <!-- <link rel="stylesheet" href="{{asset('css/toggle_Switch.css')}}"> -->
 <link rel="stylesheet" href="{{asset('css/toggle_Switch02.css')}}">
 <link href="{{asset('vendors/fontawesome-free-6.6.0-web/css/all.min.css')}}" rel="stylesheet" />
 <link href="{{asset('vendors/Toast/jquery.toast.min.css')}}" rel="stylesheet" />
  
 <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/base/jquery-ui.css">
 <link href="{{asset('vendors/fontawesome-free-6.6.0-web/css/all.min.css')}}" rel="stylesheet" />
  <!-- CSS do SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<link rel="stylesheet" href="{{ asset('build/assets/app-c2a4d485.css') }}">  

<!-- @vite(['resources/scss/app.scss', 'resources/js/app.js']) -->
<!-- include SUMMER NOTE css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<!-- include DATATABLE -->

<link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-2.1.2/af-2.7.0/b-3.1.0/b-colvis-3.1.0/b-html5-3.1.0/b-print-3.1.0/cr-2.0.3/date-1.5.3/sc-2.4.3/sb-1.7.1/sp-2.3.1/datatables.min.css" rel="stylesheet">
 
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<!-- Hotjar Tracking Code for facilitrip/toy admin -->
<script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:5318986,hjsv:6};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
</script>
  @yield('assets')
  @stack('assets')
  
  <style>
    .navbar-vertical .navbar-nav .nav-item .nav-link .icon i {
      color: #1D3857 !important;
      font-size: 15px;
    }

    .cabecalho{
      font-weight: 700;
    }

    nav .justify-between{
        display: none;
    }

    nav .hidden .relative a svg{
        width: 2% !important;
    }
    nav .hidden .relative span svg{
        width: 2% !important;
    }

    .hidden .relative span span{
        background: #ed3237 !important;
        color: #fff;
    }

    .hidden .relative span .rounded-l-md{
        background: #fff !important;
        color: #67748E;
    }

    .hidden .relative span .rounded-r-md{
        background: #fff !important;
        color: #67748E;
    }

    .icon i{
        font-size: 15px;
        color: #ed3237 !important;
    }

.navbar-vertical .navbar-nav>.nav-item .nav-link.active .icon {
    
    background-image: linear-gradient(310deg, rgb(229, 228, 228) 0%, rgb(229, 228, 228) 100%);
  } 
    
   

@media (max-width: 950px) {
    nav .hidden .relative a svg{
        width: 10% !important;
    }
    nav .hidden .relative span svg{
        width: 10% !important;
    }

    
}

  </style>
</head>

<body class="g-sidenav-show  bg-gray-100">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main">
    
    @include('layouts._aside')
  </aside>

  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
   
  <div class="justify-content-end pt-4">
   <div class="row">
    <div class="col-10">
      
    <div class="nameUser p-2 ms-5">
      Olá, {{Auth::user()->name }} 
      
      @if (Auth::user()->role == 'franqueado')
       <br><a href="{{route('admin.franquias.edit', Auth::user()->franquia->id)}}"><small class="text-bold text-decoration-underline"> ({{Auth::user()->franquia->nome_franquia}})  </small></a>
      @else
        @if(Auth::user()->franquia) 
        <br><small class="text-bold"> ({{Auth::user()->franquia->nome_franquia}})  </small>
        @endif
      @endif
      
    </div>
    </div>
    <div class="col-2">
    <div class="col-12 text-end pe-5">
        @if(Auth::check())
          <form method="POST" action="{{ route('logout') }}">
          @csrf
            <button class="btn btn-light" type="submit"><i class="fas fa-right-from-bracket mx-1"></i>Sair</button>
        
          </form>
        @endif
      </div>
    </div>
   </div>
    <!-- <div class="col-9">
        <h4>{{Route::current()->getName()}}</h4>
    </div> -->
    
    

  </div>

  <!-- CONTEUDO -->
    <div class="container-fluid pt-5">
       @yield('content')
    </div>
  </main>

  <script src="{{ asset('build/assets/app-a83ed21d.js') }}"></script>

  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
  
  <!-- SUmmer Note -->
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
  
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  
<!-- Bootstrap JavaScript --><script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
  <!-- Account MOney -->
  <script src="{{asset('assets/js/accounting.min.js')}}"></script>

<!-- include DATATABLE -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-2.1.2/af-2.7.0/b-3.1.0/b-colvis-3.1.0/b-html5-3.1.0/b-print-3.1.0/cr-2.0.3/date-1.5.3/sc-2.4.3/sb-1.7.1/sp-2.3.1/datatables.min.js"></script>

  <!--   Core JS Files   -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="{{asset('/assets/js/core/popper.min.js')}}"></script>
  
  <script src="{{asset('/assets/js/plugins/perfect-scrollbar.min.js')}}"></script>
  <script src="{{asset('/assets/js/plugins/smooth-scrollbar.min.js')}}"></script>
  <script src="{{asset('/vendors/Toast/jquery.toast.min.js')}}"></script>

  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" integrity="sha512-pHVGpX7F/27yZ0ISY+VVjyULApbDlD0/X0rgGbTqCE7WFW5MezNTWG/dnhtbBuICzsd0WQPgpE4REBLv+UqChw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script><!-- Custom Javascript -->
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/pt.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  
  <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBPg5kCJsqs60bO9SMhMyTQg4zGgANJpR4"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBPg5kCJsqs60bO9SMhMyTQg4zGgANJpR4&libraries=places"></script> -->

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyATu3LXXWnjLPyTje84fXVoQBafTmf_2oc"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyATu3LXXWnjLPyTje84fXVoQBafTmf_2oc&libraries=places"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
  <script>
    flatpickr(".data_nascimento_flatpicker", {
        dateFormat: "Y-m-d",  
        maxDate: "today",     
        altInput: true,       
        altFormat: "d/m/Y",   
        allowInput: true,  
    });
 
     $(".flatPicker").flatpickr({
            
            altInput: true,
            altFormat: "d/m/Y",
            dateFormat: "Y-m-d",
            locale: "pt",
       //     minDate: "today",
            "disable": [
                function(date) {
                    // return true to disable
                    return (date.getDay() === 0);
    
                }
            ], 
        })


// Status Off-line
   $("body").on('change' , '.form-switch .form-check-input',function(){
   
   if($(this).is(':checked')){
     $(this).siblings('label').html('Ativo')
     $(this).val('ativo');
   }else{
     $(this).siblings('label').html('Inativo')
   }
 })

    function getMoney(numero) {
         return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(numero).replace('R$', '');
    }
// MASCARAS
    var SPMaskBehavior = function (val) {
      return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
    },
    spOptions = {
      onKeyPress: function(val, e, field, options) {
          field.mask(SPMaskBehavior.apply({}, arguments), options);
        }
    };

    $('.phoneMask').mask(SPMaskBehavior, spOptions);
    $('.moneyMask').mask("#.##0,00", {reverse: true});
    $('.cepMask').mask('00000-000');
    $('.cpfMask').mask('000.000.000-00', {reverse: true});
    $('.cnpjMask').mask('00.000.000/0000-00', {reverse: true});
    $('.creditCardMask').mask('0000 0000 0000 0000');
    $('.expirationDateMask').mask('00/00');
    $('.celMask').mask('(00) 00000-0000');

    
// jQuery COLLAPSE  
$("body").on('click','.tooglegeCollapse',function(e){
        
        e.preventDefault();
        var alvo = $(this).data('target');
        $(".collapse").not(alvo).removeClass('show');
        $(alvo).toggleClass('show')
    })
   
// DESTROY
    $(".btn-destroy").click(function(e){

      var url = $(this).attr('href');
      e.preventDefault();

      $(this).closest('tr').addClass("remove-row");
      $(this).closest('.row').addClass("remove-row");

      swal({
        title: "Você tem certeza?",
        text: "Você removerá permanentemente este item",
        icon: "warning",
        dangerMode: true,
        buttons:{
          cancel: {
            text: "Cancel",
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
      }) .then(willDelete => {
       if (willDelete) {

        $.ajax({
            url: url,
            type: 'GET',
            success: function(data){ 
                if (willDelete) {
                    $(".remove-row").remove();
                swal("Sucesso!", "Item removido com sucesso", "success");
                }
            },
            error: function(err) {
               var erro = err.responseJSON
                swal("Atenção!", erro.error, "error");
            }
        });

        
       }
     });
    })

    function buscaCep(cep) {
        $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {
            $("input[name='endereco']").val(dados.logradouro)
            $("input[name='bairro']").val(dados.bairro)
            $("input[name='cidade']").val(dados.localidade)
            $("input[name='estado']").val(dados.uf)
            $("input[name='pais']").val("Brasil")

        });
    }
    $("#buscaCep").change(function () {
        buscaCep($(this).val())
    });

    $("#searchCep").click(function (e) {
        e.preventDefault();
        buscaCep($("#buscaCep").val())
    })

// GetMoney e SaveMoney
    function saveMoney($value) {

    if ($value === null) {
        return 0.00;
    }
    var $money = $value.replace(".", "");

    $money = $money.replace(",", ".", $money);

    return $money;
    }

    function getMoney($value) {

    if ($value === null) {
        return '';
    }

    return accounting.formatMoney($value,'', 2, ".", ",");
    }

</script>

<script>

// CPF VALIDADOR
    $('#cpf').on('change', function() {

        var cpfInput = this.value;

        if (validaCPF(cpfInput)) { 
          
         return true

        } else {
          
            swal.fire({
                  title: "CPF inválido!",
                  icon: "error",
                  }).then(function() {
            
                    $('#cpf').val('');
                  });
        }
    });

      function validaCPF(cpf) {
        var Soma = 0
        var Resto

        var strCPF = String(cpf).replace(/[^\d]/g, '')
        
        if (strCPF.length !== 11)
          return false
        
        if ([
          '00000000000',
          '11111111111',
          '22222222222',
          '33333333333',
          '44444444444',
          '55555555555',
          '66666666666',
          '77777777777',
          '88888888888',
          '99999999999',
          ].indexOf(strCPF) !== -1)
          return false

        for (i=1; i<=9; i++)
          Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (11 - i);

        Resto = (Soma * 10) % 11

        if ((Resto == 10) || (Resto == 11)) 
          Resto = 0

        if (Resto != parseInt(strCPF.substring(9, 10)) )
          return false

        Soma = 0

        for (i = 1; i <= 10; i++)
          Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (12 - i)

        Resto = (Soma * 10) % 11

        if ((Resto == 10) || (Resto == 11)) 
          Resto = 0

        if (Resto != parseInt(strCPF.substring(10, 11) ) )
          return false

        return true
      }

  $("body").on("click", ".btn-delete", function(e){
    var url = $(this).attr('href');
    e.preventDefault();
    var $rowToRemove = $(this).closest('tr');

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
    }).then(willDelete => {
        if (willDelete) {
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    $rowToRemove.remove();
                    swal("Sucesso!", "Item removido com sucesso", "success").then(function() {
                        location.reload();
                    });
                },
                error: function(err) {
                    var erro = err.responseJSON;
                    swal("Atenção!", erro.error, "error");
                }
            });
        }
    });
});
 
</script>

@yield('scripts')
@stack('scripts')
</body>

</html>