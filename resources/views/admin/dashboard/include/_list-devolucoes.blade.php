
<div class="row bg-dark arial16-font text-light text-bold m-0 py-2">
    <div class="col-2">Data</div>
    <div class="col-4">Pedido</div>
    <div class="col-2">Logistica</div>
    <div class="col-2">Situação</div>
   
    <div class="col-2 text-center">Ações</div>
</div>

@foreach($devolucoes as $k => $devolucao)
    <div class="row m-0 py-2 border-bottom arial12-font align-items-center {{ $loop->index % 2 == 0 ? 'bg-light' : 'bg-white' }}">
        <div class="col-2">
            {{ \Carbon\Carbon::parse($devolucao->data_devolucao)->format('d/m/Y') }} 
        </div>
        <div class="col-4">
            {{$devolucao->pedido->numero ?? ''}}</br>
            {{$devolucao->cliente->name ?? ''}} 
        </div>
        <div class="col-2">
            {{$devolucao->pedido->tipo_frete ?? ''}}
        </div>
        <div class="col-2">
            @if($devolucao->pedido->status->id == 1)
                <i class="fas fa-circle" style="color: red;"></i> 
            @elseif($devolucao->pedido->status->id == 2)
                <i class="fas fa-circle" style="color: #ff7f00;"></i>
            @elseif($devolucao->pedido->status->id == 3)
                 <i class="fas fa-circle" style="color: green;"></i> 
            @elseif($devolucao->pedido->status->id == 4)
                 <i class="fas fa-circle" style="color: green;"></i>
            @elseif($devolucao->pedido->status->id == 5)
                 <i class="fas fa-circle" style="color: green;"></i>
            @elseif($devolucao->pedido->status->id == 6)
                 <i class="fas fa-circle" style="color: blue;"></i> 
            @elseif($devolucao->pedido->status->id == 7)
                 <i class="fas fa-circle" style="color: gray;"></i> 
            @elseif($devolucao->pedido->status->id == 8)
                 <i class="fas fa-circle" style="color: purple;"></i> 
            
            @endif
            {{$devolucao->pedido->status->nome}}
        </div>
        <div class="col-2 text-center">
            <a href="{{route('admin.pedidos.preview', $devolucao->pedido->id)}}" class="btn btn-info btn-sm show btn-icon-only m-0 preview-franquia"><i class="fas fa-eye"></i></a>
        </div>
    </div>
@endforeach


    <div class="row mt-5 justify-content-center">
        <div class="col-sm-12 mx-auto align-center paginacaoDevolucoes">
        {{ $devolucoes->links() }}  
        </div>
    </div>

    