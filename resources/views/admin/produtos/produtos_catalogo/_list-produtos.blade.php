<div class="col-12">
    <div class="row bg-dark arial16-font text-light text-bold m-0 py-2">
        <div class="col-3">Produto</div>
        <div class="col-2">Valor Diário</div>
        <div class="col-2">Categoria</div>
        <div class="col-2 ps-5">Link</div>
        <div class="col-1 text-center">Status</div>
        <div class="col-2 text-center">Ações</div>
    </div>
</div>
<div class="col-12 mt-4">
    @foreach($produtos as $k => $prod)
    <div class="row m-0 py-2 border-bottom arial14-font-normal ">

        <div class="col-3">
            {{$prod->nome ?? ''}}
        </div>
        <div class="col-2">
            R$ {{getMoney($prod->valor_base_diaria) ?? ''}}
        </div>
        <div class="col-2">
            {{$prod->categoria->nome ?? ''}}
        </div>
        <div class="col-2">
            <a href="{{$prod->link()}}" target="_blank" class="btn btn-sm btn-primary">Link</a>
        </div>
        <div class="col-1 justify-content-center d-flex">
            <div class="form-check form-switch">
                <input class="form-check-input status-categoria"
                    type="checkbox" name="status" role="switch"
                    value="ativo"
                    data-id="{{$prod->id}}"
                    @if($prod->status == 'ativo')
                checked
                @endif>
                <label class="form-check-label" for="flexSwitchCheckChecked">@if($prod->status == 'ativo') Ativo @else Inativo @endif</label>
            </div>
        </div>
        <div class="col-2 text-center">
        @if(permissao('produtos')->editar == 'sim')
            <a href="{{route('admin.produtos.edit', $prod->id)}}" class="btn btn-sm btn-icon-only btn-secondary edit"> <i class="fas fa-pencil"></i> </a>
        @endif
        @if(permissao('depoimentos')->deletar == 'sim')
            <a href="{{route('admin.produtos.delete', $prod->id)}}" class="btn btn-sm btn-icon-only btn-danger btn-exclude"> <i class="fas fa-trash"></i> </a>
        @endif
        </div>
    </div>
    @endforeach
</div>