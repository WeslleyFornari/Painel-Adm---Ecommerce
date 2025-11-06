
<div class="col-12">
<div class="row bg-dark arial16-font text-light text-bold m-0 py-2">

        <div class="col-2">Franquia</div>
        <div class="col-2">CEP</div>
        <div class="col-4">Localização</div>
        <div class="col-2 text-center">Status</div>
        <div class="col-2 text-center">Ações</div>
    </div>
</div>
<div class="col-12 mt-4">
@foreach($cep_bloqueados as $k => $bloqueado)
<div class="row m-0 py-2 border-bottom arial14-font-normal ">
        <div class="col-2">
            {{$bloqueado->franquia->nome_responsavel ?? ''}}
        </div>
        <div class="col-2">
            {{$bloqueado->cep ?? ''}}
        </div>
        <div class="col-4">
            {{$bloqueado->endereco ?? ''}} - {{$bloqueado->bairro ?? ''}}<br>
            {{$bloqueado->cidade ?? ''}} - {{$bloqueado->estado ?? ''}}, {{$bloqueado->pais ?? ''}}
        </div>
        <div class="col-2 justify-content-center d-flex">
            <div class="form-check form-switch">
                <input class="form-check-input status-categoria"
                    type="checkbox" name="status" role="switch" 
                    value="ativo"
                    data-id="{{$bloqueado->id}}"
                    @if($bloqueado->status == 'ativo')
                    checked
                    @endif>
                <label class="form-check-label" for="flexSwitchCheckChecked"> @if($bloqueado->status == 'ativo') Ativo @else Inativo @endif</label>
            </div>
        </div>
        <div class="col-2 text-center">
            @if(permissao('cep_bloqueados')->editar == 'sim')
            <a href="{{route('admin.cep_bloqueados.edit', $bloqueado->id)}}" class="btn btn-sm btn-icon-only btn-secondary"> <i class="fas fa-pencil"></i> </a>
            @endif
            @if(permissao('cep_bloqueados')->deletar == 'sim')
            <a href="{{route('admin.cep_bloqueados.delete', $bloqueado->id) }}" class="btn btn-sm btn-icon-only btn-danger btn-delete"> <i class="fas fa-trash"></i> </a>           
            @endif
        </div>
    </div>
@endforeach
</div>
            
    
          


