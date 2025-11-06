
<div class="row bg-dark arial16-font text-light text-bold m-0 py-2">
    <div class="col-2">Data</div>
    <div class="col-4">Pedido</div>
    <div class="col-2">Logistica</div>
    <div class="col-2">Situação</div>
   
    <div class="col-2 text-center">Ações</div>
</div>

@foreach($entregas as $k => $entrega)
    <div class="row m-0 py-2 border-bottom arial12-font align-items-center {{ $loop->index % 2 == 0 ? 'bg-light' : 'bg-white' }}">
        <div class="col-2">
            {{ \Carbon\Carbon::parse($entrega->data_entrega)->format('d/m/Y') }} 
        </div>
        <div class="col-4">
            {{$entrega->pedido->numero ?? ''}}</br>
            {{$entrega->cliente->name ?? ''}} 
        </div>
        <div class="col-2">
            {{$entrega->pedido->tipo_frete ?? ''}}
        </div>
        <div class="col-2">
            @if($entrega->pedido->status->id == 1)
                <i class="fas fa-circle" style="color: red;"></i> 
            @elseif($entrega->pedido->status->id == 2)
                <i class="fas fa-circle" style="color: #ff7f00;"></i>
            @elseif($entrega->pedido->status->id == 3)
                 <i class="fas fa-circle" style="color: green;"></i> 
            @elseif($entrega->pedido->status->id == 4)
                 <i class="fas fa-circle" style="color: green;"></i>
            @elseif($entrega->pedido->status->id == 5)
                 <i class="fas fa-circle" style="color: green;"></i>
            @elseif($entrega->pedido->status->id == 6)
                 <i class="fas fa-circle" style="color: blue;"></i> 
            @elseif($entrega->pedido->status->id == 7)
                 <i class="fas fa-circle" style="color: gray;"></i> 
            @elseif($entrega->pedido->status->id == 8)
                 <i class="fas fa-circle" style="color: purple;"></i> 
            
            @endif
            {{$entrega->pedido->status->nome}}
        </div>
        <div class="col-2 text-center">
            <a href="{{route('admin.pedidos.preview', $entrega->pedido->id)}}" class="btn btn-info btn-sm show btn-icon-only m-0 preview-franquia"><i class="fas fa-eye"></i></a>
        </div>
    </div>
@endforeach


    <div class="row mt-5 justify-content-center">
        <div class="col-sm-12 mx-auto align-center paginacaoEntregas">
        {{ $entregas->links() }}  
        </div>
    </div>

    