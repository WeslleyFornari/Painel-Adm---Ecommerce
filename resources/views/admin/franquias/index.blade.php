@extends('layouts.app')


@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">

            <div class="card mb-4">

                <div class="card-header d-flex pb-0">
                    <div class="col-6">
                        <h4>Franquias</h4>
                    </div>
                    @if(permissao('franquias')->criar == 'sim')
                    <div class="col-6 text-end">
                        <a href="{{route('admin.franquias.new')}}" class="btn btn-primary">Adicionar</a>
                    </div>
                    @endif
                </div>

                <div class="p-4">

                    <div class="row bg-dark arial16-font text-light text-bold m-0 py-2">
                        <div class="col-1">Tipo</div>
                        <div class="col-2">Nome</div>
                        <div class="col-3">Responsável</div>
                        <div class="col-2">Telefone 1</div>
                        <div class="col-1">Status</div>

                        <div class="col text-center">Ações</div>
                    </div>

                    @foreach($franquias as $key => $value)
                        <div class="row m-0 py-2 border-bottom arial14-font-normal align-items-center">

                            <div class="col-1">{{ $value->tipo_franqueado ?? ''}}</div>
                            <div class="col-2">{{ $value->nome_franquia ?? '' }}</div>
                            <div class="col-3">{{ $value->nome_responsavel ?? '' }}</div>
                            <div class="col-2 phoneMask">{{ $value->celular ?? '' }}</div>


                            <div class="col-1 justify-content-center d-flex">
                                <div class="form-check form-switch">
                                    <input class="form-check-input status-categoria"
                                        type="checkbox" name="status" role="switch" 
                                        value="ativo"
                                        data-id="{{$value->id}}"
                                        @if($value->status == 'ativo')
                                        checked
                                        @endif>
                                    <label class="form-check-label" for="flexSwitchCheckChecked">@if($value->status == 'ativo') Ativo @else Inativo @endif</label>
                                </div>
                            </div>

                            <div class="col text-center">
                            @if(permissao('franquias')->visualizar == 'sim')
                                <a href="{{ route('admin.franquias.preview',$value->id) }}"
                                    class="btn btn-info btn-sm show btn-icon-only m-0 preview-franquia"><i class="fas fa-eye"></i></a>
                            @endif
                            @if(permissao('franquias')->editar == 'sim')
                                <a href="{{ route('admin.franquias.edit', $value->id) }}"class="btn btn-sm btn-warning btn-icon-only m-0"><i class="fa fa-pencil bg-amarelo"></i></a>
                            @endif
                            @if(permissao('franquias')->deletar == 'sim')
                                <a href="{{ route('admin.franquias.delete',$value->id) }}"class="btn btn-danger btn-sm btn-destroy  btn-icon-only m-0"><i class="fas fa-trash bg-rosa"></i></a>
                            @endif
                            </div>
                            
                        </div>
                        @endforeach     

                        <div class="row mt-5 justify-content-center">
                            <div class="col-sm-12 mx-auto align-center">
                            {{ $franquias->links() }}  
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal-->
<div class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" role="dialog" aria-hidden="true" id="ModalFranquia">

<div class="modal-dialog modal-xl" >
    <div class="modal-content">

        <div class="modal-header">
        <h5 class="modal-title">Detalhes da Franquia</h5>
        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close"><i class="fa-regular fa-circle-xmark"></i></button>
        </div>
      
        <div class="modal-body" id="conteudo-franquia">
                        
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

    var url = '{{ route("admin.franquias.mudarStatus", ["id" => ":id"]) }}';
    url = url.replace(':id', id);
    $.get(url, function (data) {
        swal({
            title: "Parabéns",
            text: "Status alterado com sucesso!.",
            icon: "success",
        })
    });
});

$("body").on('click','.preview-franquia',function() {
       
       event.preventDefault();
       var url = $(this).attr('href'); 
       console.log(url);

       $.ajax({
           url: url,
           type: "GET",
        
           success: function(response) {

               $("#conteudo-franquia").html(response);
               $("#ModalFranquia").modal('show')

           },       
       });
   });

</script>
@endsection