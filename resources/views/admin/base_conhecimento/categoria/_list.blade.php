
<div class="col-12">
<div class="row bg-dark arial16-font text-light text-bold mx-4 m-0 py-2">

        <div class="col-3">Título</div>
        <div class="col-3 text-center">Tipo</div>
        <div class="col-3 text-center">Status</div>
        <div class="col-3 text-center">Ações</div>
    </div>
</div>

<div class="col-12 mt-4">
@foreach($base_conhecimento_categorias as $k => $base_cat)
<div class="row m-0 py-2 border-bottom arial14-font-normal mx-4">
        <div class="col-3">
            {{$base_cat->titulo ?? ''}}
        </div>
        <div class="col-3 text-center">
            {{$base_cat->tipo ?? ''}}
        </div>
        <div class="col-3 justify-content-center d-flex">
            <div class="form-check form-switch">
                <input class="form-check-input status-categoria"
                    type="checkbox" name="status" role="switch" 
                    value="ativo"
                    data-id="{{$base_cat->id}}"
                    @if($base_cat->status == 'ativo')
                    checked
                    @endif>
                <label class="form-check-label" for="flexSwitchCheckChecked"> @if($base_cat->status == 'ativo') Ativo @else Inativo @endif</label>
            </div>
        </div>
        <div class="col-3 text-center">
            <a href="{{route('admin.base_conhecimento_categoria.edit', $base_cat->id)}}" class="btn btn-sm btn-icon-only btn-secondary edit"> <i class="fas fa-pencil"></i> </a>
            <a href="{{route('admin.base_conhecimento_categoria.delete', $base_cat->id) }}" class="btn btn-sm btn-icon-only btn-danger btn-delete"> <i class="fas fa-trash"></i> </a>           
        </div>
    </div>
@endforeach
</div>
            
    
          


