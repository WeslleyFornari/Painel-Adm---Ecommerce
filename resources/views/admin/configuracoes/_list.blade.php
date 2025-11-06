
<div class="col-12">
<div class="row bg-dark arial16-font text-light text-bold m-0 py-2">

        <div class="col-3">Titulo</div>
        <div class="col-2">Tipo</div>
        <div class="col-2">Parametro</div>
        <div class="col-3">Valor</div>
       
        <div class="col-2 text-center">Ações</div>
    </div>
</div>

<div class="col-12 mt-4">
@foreach($configuracoes as $k => $config)
<div class="row m-0 py-2 border-bottom arial14-font-normal ">

        <div class="col-3">
            {{$config->titulo ?? ''}}
        </div>
        <div class="col-2">
            {{$config->tipo_franqueado ?? ''}}
        </div>
        <div class="col-2">
            {{$config->param ?? ''}}
       </div>
        <div class="col-3">
            {{$config->value ?? ''}}
        </div>
      
     
        <div class="col-2 text-center">
            @if(permissao('configuracoes')->editar == 'sim')
            <a href="{{route('admin.configuracoes.edit', $config->id)}}" class="btn btn-sm btn-icon-only btn-secondary editar-configuracao"> <i class="fas fa-pencil"></i> </a>
            @endif
            @if(permissao('configuracoes')->deletar == 'sim')
            <a href="{{route('admin.configuracoes.delete', $config->id) }}" class="btn btn-sm btn-icon-only btn-danger deletar-configuracao"> <i class="fas fa-trash"></i> </a>           
            @endif
        </div>
    </div>
@endforeach
</div>
            
    
          


