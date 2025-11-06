<div class="row bg-dark arial16-font text-light text-bold m-0 py-2">



    <div class="col-2 text-center">Codigo </div>
    <div class="col-2 text-center">Modalidade </div>
    <div class="col-1 text-center">Qtd </div>
    <div class="col-1 text-center">Valor </div>
    <div class="col-2 text-center">Valor Mínimo </div>
    <div class="col-2 text-center">Tipo </div>
    <div class="col-1 text-center">Status </div>
    <div class="col-1 text-center">Ações</div>
</div>

<!--BODY-->
@foreach($cupons as $k => $cupom)
<div class="row m-0 py-2 border-bottom arial14-font-normal align-cupoms-center">


    <div class="col-2 text-center">{{$cupom->codigo ?? ''}} <br> @if($cupom->tipo_franqueado == 'trip')<small>facili{{$cupom->tipo_franqueado}}</small>
        @else<small>facili{{$cupom->franquia->nome_franquia ?? ''}}</small>@endif</div>

    <div class="col-2 text-center">{{$cupom->modalidade ?? ''}}</div>
    <div class="col-1 text-center">{{$cupom->cuponsDisponiveis()}} / {{$cupom->qtd ?? ''}}</div>
    <div class="col-1 text-center">{{getMoney($cupom->valor ?? '')}}</div>
    <div class="col-2 text-center">{{getMoney($cupom->valor_minimo ?? '')}}</div>
    <div class="col-2 text-center">{{$cupom->tipo ?? ''}}</div>


    <div class="col-1 justify-content-center d-flex">
    @if(Auth::user()->role == 'admin' || Auth::user()->franquia->tipo_franqueado == 'toy')
        <div class="form-check form-switch">
            <input class="form-check-input status-categoria"
                type="checkbox" name="status" role="switch"
                value="ativo"
                data-id="{{$cupom->id}}"
                @if($cupom->status == 'ativo')
            checked
            @endif>
            <label class="form-check-label" for="flexSwitchCheckChecked">@if($cupom->status == 'ativo') Ativo @else Inativo @endif</label>
        </div>
    @endif    
    </div>

    <div class="col-1 text-center">
        @if(permissao('cupons')->editar == 'sim')
            <a href="{{route('admin.cupons.edit', $cupom->id)}}" class="btn btn-sm btn-icon-only btn-secondary edit"> <i class="fas fa-pencil"></i> </a>
        @endif
        @if(permissao('cupons')->deletar == 'sim')
            <a href="{{route('admin.cupons.delete', $cupom->id) }}" class="btn btn-sm btn-icon-only btn-danger btn-destroy"> <i class="fas fa-trash"></i> </a>
        @endif
    </div>
</div>

@endforeach
<div class="row mt-5 justify-content-center">
    <div class="col-sm-12 mx-auto align-center">
        {!!$cupons->links()!!}
    </div>
</div>