@extends('layouts.app')

@section('assets')
<style>
  .navbar {
    box-shadow: none;
  }


  .form-check .situacao {
    margin-top: 0;
    vertical-align: middle;
  }

  .table-responsive {
    max-height: 500px; 
    overflow-y: auto; 
    overflow-x: auto; 
  }

  .table {
    white-space: nowrap; 
  }
  .table td, .table th {
    max-width: 250px; 
    overflow: hidden;
    text-overflow: ellipsis; 
  }
  section {
    overflow-x: hidden; 
    max-width: 100%; 
    box-sizing: border-box; 
}


</style>

<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{asset('css/estilos.css')}}">
@endsection

@section('content')
<section>
<div class="row justify-content-center">
  <div class="col-md-12">

    <div class="card">
      <div class="card-body">

        <div class="row mb-">
          <div class="col-4 ps-4 my-2">
            <h4 class="tituloRelatorio">Relatórios</h4>
          </div>
          <div class="col-8 ps-4">

            <nav class="navbar navbar-expand-lg navbar-white bg-white">
              <div class="container-fluid">
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                  <ul class="navbar-nav">
                    <li class="nav-item dropdown mx-3">
                      <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Pedidos
                      </a>
                      <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <li>
                          <buttom class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalPedidos">Relatório de Todos os Pedidos</buttom>
                        </li>
                        <li>
                          <buttom class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalClientes">Relatório de Pedidos por Clientes</buttom>
                        </li>
                        <li>
                          <buttom class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalSemPagto">Relatório de Pedidos Confirmados Sem Pagamentos</buttom>
                        </li>
                      </ul>
                    </li>
                    <li class="nav-item dropdown mx-3">
                      <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Itens
                      </a>
                      <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2">
                        <li>
                          <buttom class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalCurvaABC">Curva ABC do Estoque * </buttom>
                        </li>
                      </ul>
                    </li>
                    <li class="nav-item dropdown mx-3">
                      <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Clientes
                      </a>
                      <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2">
                        <li><button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalclientes">Relatório de todos os clientes</button></li>
                        <li><button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalClientesMaisPedidos">Mais realizam pedidos</button></li>
                        <li><button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalClientesMaisGastam">Mais gastam em pedidos</button></li>
                      </ul>
                    </li>
                    <!-- <li class="nav-item dropdown mx-3">
                                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Usuarios
                                  </a>
                                  <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2">
                                    <li><a class="dropdown-item" href="#">Todos</a></li>
                                    <li><a class="dropdown-item" href="#">Por Cliente</a></li>
                                    <li><a class="dropdown-item" href="#">Sem Pagamento</a></li>
                                    <li><a class="dropdown-item" href="#">Sem Programação</a></li>
                                  </ul>
                                </li> -->
                    <li class="nav-item dropdown mx-3">
                      <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Logística
                      </a>
                      <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2">
                        <li>
                          <buttom class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalItensLogistica">Relátorio de Separação de Itens para Logística</buttom>
                        </li>
                        <li>
                          <buttom class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalLogistica">Relatório de Logística </buttom>
                        </li>
                      </ul>
                    </li>
                    <li class="nav-item dropdown mx-3">
                      <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Financeiro
                      </a>
                      <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2">
                        <li><button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalfinanceiro">Relatório de Transações Financeiras </button></li>
                        <li><button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalpagamentos">Relatório de Pagamentos dos Pedidos</button></li>
                      </ul>
                    </li>
                    <li class="nav-item dropdown mx-3">
                      <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Estoque
                      </a>
                      <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2">
                        <li>
                          <buttom class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalEstoque">Itens em estoque </buttom>
                        </li>
                      </ul>
                    </li>
                  </ul>
                </div>
              </div>
            </nav>

          </div>
        </div>
        <hr>
        <div id="lista-Relatorios" class="mt-4 lista-Relatorios row">
        </div>

      </div>
    </div>
  </div>
</div>
</section>
@include('admin.relatorios.modals')
@endsection


@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  $(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap-5'
    });

    $('body').on('change', '.recente', function(e) {

      var nome = $(this).data('nome');
      var tipo = $(this).val();

      if (tipo == 'data_especifica') {
        $('#data_especifica_' + nome).css('opacity', '1');
        $('#data_especifica_' + nome).find(':input').prop('disabled', false);
        $('#por_periodo_' + nome).css('opacity', '0.5');
        $('#por_periodo_' + nome).find(':input').prop('disabled', true);

      } else if (tipo == 'por_periodo') {
        $('#por_periodo_' + nome).css('opacity', '1');
        $('#por_periodo_' + nome).find(':input').prop('disabled', false);
        $('#data_especifica_' + nome).css('opacity', '0.5');
        $('#data_especifica_' + nome).find(':input').prop('disabled', true);

      } else if (tipo == 'hoje') {
        $('#por_periodo_' + nome).css('opacity', '1');
        $('#por_periodo_' + nome).find(':input').prop('disabled', true);
        $('#data_especifica_' + nome).css('opacity', '0.5');
        $('#data_especifica_' + nome).find(':input').prop('disabled', true);

      } else {
        $('#por_periodo_' + nome).css('opacity', '0.5');
        $('#por_periodo_' + nome).find(':input').prop('disabled', true);
        $('#data_especifica_' + nome).css('opacity', '0.5');
        $('#data_especifica_' + nome).find(':input').prop('disabled', true);
      }

    

    });
  });
</script>

<script>
  $("body").on('click', '.preview-pedido', function() {

    event.preventDefault();
    var url = $(this).attr('href');
    console.log(url);

    $.ajax({
      url: url,
      type: "GET",
      success: function(response) {

        $("#conteudo-pedido").html(response);


      },
    });
  });
</script>
<script>
  // $("body").on('change', '.recente', function (e) {
  //     e.preventDefault();
  //     var tipo = $(this).val();
  //     if (tipo == 'data_especifica') {
  //         $('#data_periodo').css('opacity', '1');
  //         $('#data_periodo').find(':input').prop('disabled', false);
  //     } else {
  //         $('#data_periodo').css('opacity', '0.5');
  //         $('#data_periodo').find(':input').prop('disabled', true);
  //     }
  // });
</script>
<script>

  var data = {};

  $('.filterForm').on('submit', function(e) {

    e.preventDefault();
    var url = $(this).attr('action');
    var data = new URLSearchParams(new FormData(this)).toString();

    submitFiltro(url, data)
  });

  $("body").on('click', '.pagination .page-link', function(e) {

    e.preventDefault();
    var url = $(this).attr('href');
    var data = new URLSearchParams(new FormData($('.filterForm')[0])).toString();

    submitFiltro(url, data)
  });


  function submitFiltro(url, data) {
    console.log(url);
    $.ajax({
      url: url,
      method: 'GET',
      data: data,

      success: function(response) {

        console.log('resposta ok')

        $('.lista-Relatorios').html(response.html);
        $('.tituloRelatorio').html(response.titulo);

        $('.modal.show').modal('hide');
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open').removeAttr('data-bs-overflow').removeAttr('data-bs-padding-right').css('');
        
      },
      error: function(xhr) {

        console.error(xhr.responseText);
      }
    });
  }
</script>

@endsection