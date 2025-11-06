
<div class="col-12">

        <div class="row bg-dark arial16-font text-light text-bold m-0 py-2">
            <div class="col-2">Nome</div>
            <div class="col-2">Email</div>
            <div class="col-2">Telefone</div>
            <div class="col-2">Cidade</div>
            <div class="col-2 ">Estado</div>
            <div class="col-1 ">Capital</div>
            <div class="col-1 text-center">Ações</div>     
        </div>
</div>

<div class="col-12 mt-4">
@foreach($formularios as $k => $form)
<div class="row m-0 py-2 border-bottom arial14-font-normal ">

        <div class="col-2">
            {{$form->nome ?? ''}}
        </div>
        <div class="col-2">
            {{$form->email ?? ''}}
        </div>
        <div class="col-2">
            {{$form->telefone ?? ''}}
        </div>
        <div class="col-2">
            {{$form->cidade ?? ''}}
        </div>
        <div class="col-2">
            {{$form->estado ?? ''}}
        </div>
        <div class="col-1">
            {{$form->capital ?? ''}}
        </div>
     
        <div class="col-1 text-center">
        @if(permissao('formulario_contato')->criar == 'sim')
            <a href="{{route('admin.formulario_contato.delete', $form->id) }}" class="btn btn-sm btn-icon-only btn-danger btn-destroy"> <i class="fas fa-trash"></i> </a> 
        @endif
        </div>
    </div>
@endforeach
</div>
            
    
          


