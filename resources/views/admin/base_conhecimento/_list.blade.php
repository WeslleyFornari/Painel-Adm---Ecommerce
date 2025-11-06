
<div class="col-12">
<div class="row bg-dark arial16-font text-light text-bold m-0 py-2">
        <div class="col-2">Título</div>
        <div class="col-3">Descrição</div>
        <div class="col-2">Categoria</div>
        <div class="col-2 text-center">Tipo</div>
        <div class="col-1 text-center">Status</div>
        <div class="col-2 text-center">Ações</div>
    </div>
</div>

<div class="col-12 mt-4">
@foreach($bases_conhecimento as $k => $base)
<div class="row m-0 py-2 border-bottom arial14-font-normal ">
        <div class="col-2">
            {{$base->titulo ?? ''}}
        </div>
        <div class="col-3">
            {{$base->descricao ?? ''}}
        </div>
        <div class="col-2">
            {{$base->categoria?->titulo ?? ''}}
        </div>
        <div class="col-2 text-center">
            {{$base->tipo ?? ''}}
        </div>
        <div class="col-1 justify-content-center d-flex">
            <div class="form-check form-switch">
                <input class="form-check-input status-categoria"
                    type="checkbox" name="status" role="switch" 
                    value="ativo"
                    data-id="{{$base->id}}"
                    @if($base->status == 'ativo')
                    checked
                    @endif>
                <label class="form-check-label" for="flexSwitchCheckChecked"> @if($base->status == 'ativo') Ativo @else Inativo @endif</label>
            </div>
        </div>
        <div class="col-2 text-center">
            <a href="{{route('admin.base_conhecimento.edit', $base->id)}}" class="btn btn-sm btn-icon-only btn-secondary edit"> <i class="fas fa-pencil"></i> </a>
            <a href="{{route('admin.base_conhecimento.delete', $base->id) }}" class="btn btn-sm btn-icon-only btn-danger btn-delete"> <i class="fas fa-trash"></i> </a>           
        </div>
    </div>
@endforeach
</div>
            
    
          


