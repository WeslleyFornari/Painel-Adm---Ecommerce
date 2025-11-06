<div class="col-12">
    <div class="row bg-dark arial16-font text-light text-bold m-0 py-2">
        <div class="col-2">Franquia</div>
        <div class="col-2">Localização</div>
        <div class="col-2">Valor Entrega</div>
        <div class="col-2">Tempo Entrega</div>
        <div class="col-2 text-center">Status</div>
        <div class="col-2 text-center">Ações</div>
    </div>
</div>
<div class="col-12 mt-4">
    @foreach($regioes_cidades as $k => $regiaocidade)
    <div class="row m-0 py-2 border-bottom arial14-font-normal ">
        <div class="col-2">
            {{$regiaocidade?->franquia?->nome_responsavel ?? ''}} <br>
            <small class="font-weight-bold" style="font-size: x-small;">{{$regiaocidade?->franquia?->nome_franquia}}</small>
        </div>
        <div class="col-2">
            {{$regiaocidade->bairro ?? ''}}<br>
            {{$regiaocidade->cidade ?? ''}}
        </div>
        <div class="col-2">
        R$ {{ getMoney($regiaocidade->valor_entrega_expresso) }} <br>
        R$ {{ getMoney($regiaocidade->valor_entrega_economico) }}
        </div>
        <div class="col-2">
            {{$regiaocidade->tempo_entrega ?? ''}}
        </div>
        <div class="col-2 justify-content-center d-flex">
            <div class="form-check form-switch">
                <input class="form-check-input status-categoria"
                    type="checkbox" name="status" role="switch" 
                    value="ativo"
                    data-id="{{$regiaocidade->id}}"
                    @if($regiaocidade->status == 'ativo')
                    checked
                    @endif>
                <label class="form-check-label" for="flexSwitchCheckChecked"> @if($regiaocidade->status == 'ativo') Ativo @else Inativo @endif</label>
            </div>
        </div>
        <div class="col-2 text-center">
        @if(permissao('regiao_atendida')->editar == 'sim')
            <a href="{{route('admin.regiao_atendida.edit', $regiaocidade->id)}}" class="btn btn-sm btn-icon-only btn-secondary"> <i class="fas fa-pencil"></i> </a>
        @endif
        @if(permissao('regiao_atendida')->deletar == 'sim')
            <a href="{{route('admin.regiao_atendida.delete', $regiaocidade->id) }}" class="btn btn-sm btn-icon-only btn-danger btn-delete"> <i class="fas fa-trash"></i> </a> 
        @endif          
        </div>
    </div>
    @endforeach

    <div id="pagination-links2" class="mt-3">
        {{ $regioes_cidades->links('pagination::bootstrap-4') }}
    </div>
    
</div>
            
    
          


