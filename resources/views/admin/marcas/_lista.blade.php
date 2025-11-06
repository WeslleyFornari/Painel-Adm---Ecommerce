<div class="row bg-dark arial16-font text-light m-0 text-bold py-2">
    <div class="col-2">Imagem</div>
    <div class="col-2"></div>
    <div class="col-4">Nome</div>
    <div class="col-2">Status</div>
    <div class="col-2 text-center">Ação</div>
</div>

<!--BODY-->
@foreach ($marcas as $k => $item)
<div class="row m-0 py-2 border-bottom arial14-font-normal align-items-center">
    <div class="col-2">
        <img src="{{ $item->imagem?->fullpatch() }}" alt="" class="img-thumbnail">
    </div>
    <div class="col-2"></div>
        <div class="col-4">{{ $item->nome ?? '' }}</div>

        <div class="col-2  d-flex">
            <div class="form-check form-switch">
                <input class="form-check-input status-marcas" type="checkbox" name="status" role="switch"
                    value="ativo" data-id="{{ $item->id }}" @if ($item->status == '1') checked @endif>
                <label class="form-check-label" for="flexSwitchCheckChecked">
                    @if ($item->status == '1')
                        Ativo
                    @else
                        Inativo
                    @endif
                </label>
            </div>
        </div>

        <div class="col-2 text-center">
        @if(permissao('marcas')->editar == 'sim')
            <a href="{{ route('admin.marcas.edit', $item->id) }}"
                class="btn btn-icon-only btn-secondary editMarca"> <i class="fas fa-pencil"></i> </a>
        @endif
        @if(permissao('marcas')->deletar == 'sim')
            <a href="{{ route('admin.marcas.delete', $item->id) }}"
                class="btn btn-icon-only btn-danger btn-destroy"> <i class="fas fa-trash"></i> </a>
        @endif
        </div>
    </div>
@endforeach
<div class="row mt-5 justify-content-center">
    <div class="col-sm-12 mx-auto align-center">

        {{ $marcas->links() }}
    </div>
</div>
