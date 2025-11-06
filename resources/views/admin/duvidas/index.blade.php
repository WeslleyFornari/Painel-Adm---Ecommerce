@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">

        <div class="card">
            <div class="card-body">

                <div class="row mb-3">

                    <div class="col-4 ps-4 my-2">
                        <h4>Duvidas Frequentes</h4>          
                    </div>

                    <div class="col-2 text-end mt-3">
                        Procurar
                    </div>
                    <div class="col-4 mt-2">
                        <input type="text" class="form-control procurar" size="15" name="procurar" placeholder="Digite aqui">
                    </div>
                    @if(permissao('duvidas')->criar == 'sim')
                    <div class="col-2 my-2 pe-4 text-end">
                            <a href="{{route('admin.duvidas.new')}}" class="btn btn-primary">Adicionar</a>
                    </div>
                    @endif
                </div>

                <div id="lista-Duvidas">
                    @include('admin.duvidas._list')
                </div> 
           </div>
        </div>

    </div>
</div>

<!-- Modal-->
<div class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" role="dialog" aria-hidden="true" id="ModalDuvidas">

<div class="modal-dialog modal-lg" >
    <div class="modal-content">

        <div class="modal-header">
        <h5 class="modal-title">Resumo</h5>
        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close"><i class="fa-regular fa-circle-xmark"></i></button>
        </div>
      
        <div class="modal-body" id="conteudo-duvida">
                        
        </div>
    
    </div>
</div>
</div>

@endsection

@section('scripts')
<script>

$("body").on('change', '.status-duvida', function () {
    
    var id = $(this).data('id');
    var status = $(this).is(":checked") ? 'ativo' : 'inativo';

    $.ajax({
        url: '{{ route("admin.duvidas.status") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            id: id,
            status: status,
        },
        success: function (response) {
            

        }
    });
});

$(".procurar").on('input', function() {

    var procurar = $(".procurar").val().toLowerCase();

    console.log(procurar);

    $.ajax({
        url: "{{route('admin.duvidas.procurar')}}",
        type: 'POST',
        data: {

            _token: $('meta[name="csrf-token"]').attr('content'),
            procurar:procurar
        },

        success: function(response) {

            $("#lista-Duvidas").html(response);
            console.log(response);
        },
        error: function(error) {
            // Lide com erros aqui
            console.error(error);
        }
    });
});

// PREVIEW

$("body").on('click','.preview-duvidas',function() {
       
       event.preventDefault();
       var url = $(this).attr('href'); 
       console.log(url);

       $.ajax({
           url: url,
           type: "GET",
        
           success: function(response) {

               $("#conteudo-duvida").html(response);
               $("#Modalduvidas").modal('show')

           },       
       });
   });

</script>
@endsection







  