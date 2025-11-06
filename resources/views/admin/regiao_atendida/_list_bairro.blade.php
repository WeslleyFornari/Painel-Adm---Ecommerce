<div class="col-12">
    <div class="row bg-dark arial16-font text-light text-bold m-0 py-2">
        <div class="col-2">Franquia</div>
        <div class="col-2 ps-3">Bairro</div>
        <div class="col-2">Cidade</div>
        <div class="col-2">Valor Entrega</div>
        <div class="col-2">Tempo Entrega</div>
        <div class="col-1 text-center">Status</div>
        <div class="col-1 text-center">Ações</div>
    </div>
</div>

<div class="col-12 mt-4">
    @foreach($regioes as $k => $regiaobairro)
    <div class="row m-0 py-2 border-bottom arial14-font-normal ">
        <div class="col-2">
            {{$regiaobairro?->franquia?->nome_responsavel ?? ''}} <br>
            <small class="font-weight-bold" style="font-size: x-small;">{{$regiaobairro?->franquia?->nome_franquia}}</small>
        </div>
        <div class="col-2">
            <span class="{{ is_null($regiaobairro->bairro) ? 'text-danger text-center' : '' }}">
                {{ $regiaobairro->bairro ?? 'valor do frete para toda cidade' }}
            </span>
        </div>
        <div class="col-2">
            {{$regiaobairro->cidade ?? ''}}
        </div>
        <div class="col-2">
        <b>expresso:</b> R${{ getMoney($regiaobairro->valor_entrega_expresso) }} <br>
        <strong>econom. :</strong> R${{ getMoney($regiaobairro->valor_entrega_economico) }}
        </div>
        <div class="col-2">
            {{$regiaobairro->tempo_entrega ?? ''}}
        </div>

        <div class="col-1 justify-content-center d-flex">
            <div class="form-check form-switch">
                <input class="form-check-input status-categoria"
                    type="checkbox" name="status" role="switch" 
                    value="ativo"
                    data-id="{{$regiaobairro->id}}"
                    @if($regiaobairro->status == 'ativo')
                    checked
                    @endif>
               
            </div>
        </div>

        <div class="col-1 text-center">
            @if(permissao('regiao_atendida')->editar == 'sim')
                <a href="{{route('admin.regiao_atendida.edit', $regiaobairro->id)}}" class="btn btn-sm btn-icon-only btn-secondary"> <i class="fas fa-pencil"></i> </a>
            @endif
            @if(permissao('regiao_atendida')->deletar == 'sim')
                <a href="{{route('admin.regiao_atendida.delete', $regiaobairro->id) }}" class="btn btn-sm btn-icon-only btn-danger btn-delete"> <i class="fas fa-trash"></i> </a> 
            @endif            
        </div>
    </div>
    @endforeach

    <div id="pagination-links" class="mt-3">
        {{ $regioes->links('pagination::bootstrap-4') }}
    </div>
</div>
            
    
          


