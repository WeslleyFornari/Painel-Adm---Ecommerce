@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">

        <div class="card">
            <div class="card-body">

                <div class="row mb-3">

                    <div class="col-8 ps-4 my-2">
                        <h4>Banners</h4>          
                    </div>

                    @if(permissao('banners')->criar == 'sim')
                    <div class="col-4 my-2 pe-4 text-end">
                            <a href="{{route('admin.banners.new')}}" class="btn btn-primary">Adicionar</a>
                    </div>
                    @endif
                </div>

                <div id="lista-Usuarios" class="lista-Banners">
                    @include('admin.banners._list-banners')
                </div> 
           </div>
        </div>

    </div>
</div>

<!-- Modal-->
<div class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" role="dialog" aria-hidden="true" id="ModalUsuario">

<div class="modal-dialog modal-lg" >
    <div class="modal-content">

        <div class="modal-header">
        <h5 class="modal-title">Detalhes do Banner</h5>
        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close"><i class="fa-regular fa-circle-xmark"></i></button>
        </div>
      
        <div class="modal-body" id="conteudo-usuario">
                        
        </div>
    
    </div>
</div>
</div>

@endsection

@section('scripts')
<script>

$("body").on('change', '.status-categoria', function (e) {
    e.preventDefault();
    var id = this.getAttribute('data-id');
    console.log("O estado do checkbox mudou! " + id);

    var url = '{{ route("admin.banners.mudarStatus", ["id" => ":id"]) }}';
    url = url.replace(':id', id);
    $.get(url, function (data) {
        swal({
            title: "Parabéns",
            text: "Status alterado com sucesso!.",
            icon: "success",
        })
    });
});
// PREVIEW

$("body").on('click','.preview-usuario',function() {
       
       event.preventDefault();
       var url = $(this).attr('href'); 
       console.log(url);

       $.ajax({
           url: url,
           type: "GET",
        
           success: function(response) {

               $("#conteudo-usuario").html(response);
               $("#ModalUsuario").modal('show')

           },       
       });
   });

</script>
@endsection







  