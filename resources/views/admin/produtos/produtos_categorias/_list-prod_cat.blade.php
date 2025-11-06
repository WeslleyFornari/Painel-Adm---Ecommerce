
<div class="col-12">
    <div class="row bg-dark arial16-font text-light text-bold m-0 py-2">
        <div class="col-2">Nome</div>
        <div class="col-4">Descrição</div>
        <div class="col-2">Categoria</div>
        <div class="col-2">Status</div>
        <div class="col-2 text-center">Ações</div>
    </div>
</div>

<div class="col-12 mt-4">
@foreach($produtos_categorias as $k => $prod_cat)
    <div class="row m-0 py-2 border-bottom arial14-font-normal ">
        <div class="col-2">
            {{$prod_cat->nome ?? ''}}
        </div>
        <div class="col-4">
            {{$prod_cat->descricao ?? ''}}
        </div>
        <div class="col-2 text-center">
            {{$prod_cat->subcategoria?->nome ?? ''}}
        </div>

        <div class="col-2 d-flex">
            <div class="form-check form-switch">
                <input class="form-check-input status-categoria"
                    type="checkbox" name="status" role="switch" 
                    value="ativo"
                    data-id="{{$prod_cat->id}}"
                    @if($prod_cat->status == 'ativo')
                    checked
                    @endif>
                <label class="form-check-label" for="flexSwitchCheckChecked"> @if($prod_cat->status == 'ativo') Ativo @else Inativo @endif</label>
            </div>
        </div>

        <div class="col-2 text-center">
        <a href="#"class="btn btn-info btn-sm show btn-icon-only preview-categoria"><i class="fas fa-eye"></i></a>
            <a href="{{route('admin.produtos_categorias.edit', $prod_cat->id)}}" class="btn btn-sm btn-icon-only btn-secondary edit"> <i class="fas fa-pencil"></i> </a>
            <a href="{{route('admin.produtos_categorias.delete', $prod_cat->id) }}" class="btn btn-sm btn-icon-only btn-danger btn-delete"> <i class="fas fa-trash"></i> </a>           
        </div>
    </div>
@endforeach
</div>
            
    
          


