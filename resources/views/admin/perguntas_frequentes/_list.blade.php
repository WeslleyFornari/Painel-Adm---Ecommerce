<div class="col-12">
    @foreach($produtos as $k => $prod)
    <div class="col-12 mt-4">
        <h5>{{$prod->nome}}</h5>
    </div>
    <div class="row cabecalho">
        <div class="col-3">Pergunta</div>
        <div class="col-3">Resposta</div>
        <div class="col-3 text-center">Status</div>
        <div class="col-3 text-center">Ações</div>
    </div>
    @foreach($prod->perguntas as $perg)
    <div class="row">
        <div class="col-3">
            {{$perg->pergunta}}
        </div>
        <div class="col-3">
            @if ($perg->resposta)
            {{$perg->resposta}}
            @else
            <a href="{{route('admin.perguntas_frequentes.responder', $perg->id)}}" class="btn btn-sm btn-primary responder mb-0">Responder</a>
            @endif
        </div>
        <div class="col-3 justify-content-center d-flex">
            <div class="form-check form-switch">
                <input class="form-check-input status-categoria"
                    type="checkbox" name="status" role="switch"
                    value="ativo"
                    data-id="{{$perg->id}}"
                    @if($perg->status == 'ativo')
                checked
                @endif>
                <label class="form-check-label" for="flexSwitchCheckChecked"> @if($perg->status == 'ativo') Ativo @else Inativo @endif</label>
            </div>
        </div>
        <div class="col-3 text-center">
            <a href="{{route('admin.perguntas_frequentes.responder', $perg->id)}}" class="btn btn-icon-only btn-secondary responder"> <i class="fas fa-pencil"></i> </a>
            @if(permissao('perguntas_frequentes')->deletar == 'sim')
            <a href="{{route('admin.perguntas_frequentes.delete', $perg->id) }}" class="btn btn-icon-only btn-danger btn-delete"> <i class="fas fa-trash"></i> </a>
            @endif
        </div>
    </div>
    <hr>
    @endforeach
    @endforeach
</div>