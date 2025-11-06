@extends('layouts.app')

@section('assets')

<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
   
        <div class="card">
            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-7 ps-4 my-2">
                        <h4>Formulario de Interesse</h4>          
                    </div>

                </div>
               

                <div id="lista-Pedidos">
                    @include('admin.formulario_franquia._list')
                </div> 
                {{ $formularios->links() }}
           </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPedido" tabindex="-1" aria-labelledby="modalPedidoLabel" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 800px">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalPedidoLabel">Detalhes Pedido</h5>
        <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
      </div>
      <div class="modal-body" id="conteudo-pedido">

      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')


<script>



$("body").on('click','.preview-pedido',function() {
       
       event.preventDefault();
       var url = $(this).attr('href');
       console.log(url);

       $.ajax({
           url: url,
           type: "GET",
           success: function(response) {

               $("#conteudo-pedido").html(response);
               $("#modalPedido").modal('show')

           },       
       });
   });


</script>
<script>
    function initAutocomplete() {
        var input = document.getElementById('bairro');
        var options = {
            types: ['(regions)']
        };
        var autocomplete = new google.maps.places.Autocomplete(input, options);
    }
    google.maps.event.addDomListener(window, 'load', initAutocomplete);
</script>

@endsection







  