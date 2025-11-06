
<div class="p-4">
    <div class="row bg-dark arial16-font text-light text-bold m-0 py-2">
        <div class="col-2 text-center">Franquia</div>
        <div class="col-3 text-center">Produto</div>
        <div class="col-2 text-center">Código</div>
        <div class="col-2 text-center">Info</div>
        <div class="col-2 text-center">Status</div>
        <div class="col-1 text-center">Ações</div>
    </div>
@foreach($estoques as $k => $estoque)
    <div class="row m-0 py-2 border-bottom arial14-font-normal align-items-center">
        <div class="col-2">
            {{$estoque->franquia->nome_franquia ?? ''}}
        </div>
        <div class="col-3">
            {{$estoque->produto->nome ?? ''}}
        </div>
        <div class="col-2">
            {{$estoque->codigo ?? ''}}
        </div>
        <div class="col-2">
            {{ $estoque->data_compra ? \Carbon\Carbon::parse($estoque->data_compra)->format('d/m/Y') : '' }}<br>
            {{getMoney($estoque->valor_compra)}}<br>
            Uso: {{$estoque->uso()->count()}}
        </div>
        <div class="col-2">
            <select class="form-select btn-primary status" name="status" style="background-color: #00194A; font-size: 15px; font-weight: 600" data-id="{{$estoque->id}}">
                <option @if($estoque->status == 'Indisponível') selected @endif value="Indisponível">Indisponível</option>
                <option @if($estoque->status == 'Disponível') selected @endif value="Disponível">Disponível</option>
                <option @if($estoque->status == 'Manutenção') selected @endif value="Manutenção">Manutenção</option>
            </select>
        </div>
        <div class="col-1">
        @if(permissao('estoque')->editar == 'sim')
            <a href="{{route('admin.estoque.edit', $estoque->id)}}" class="btn btn-icon-only btn-secondary edit btn-sm m-0"> <i class="fas fa-pencil"></i> </a>
        @endif   
        @if(permissao('estoque')->deletar == 'sim')
            <a href="{{route('admin.estoque.delete', $estoque->id) }}" class="btn btn-icon-only btn-danger btn-exclude btn-sm m-0"> <i class="fas fa-trash"></i> </a>           
        @endif
        </div>
      
    </div>
@endforeach
</div>

<div class="p-4 d-flex justify-content-center">
  {!! $estoques->links() !!}
</div>
            
    