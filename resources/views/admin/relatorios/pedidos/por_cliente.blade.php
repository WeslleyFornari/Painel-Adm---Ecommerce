<div class="table-responsive">
@foreach($pedidos as $pedido)
    <div class="bg-dark arial14-font text-light text-bold w-100 ps-4 p-2">PEDIDO #{{$pedido->numero}} ({{$pedido->status->nome}})</div>

    <table class="table display nowrap mb-0" id="clientes" style="width:100%">
        <thead class="bg-gradient-light arial14-font text-bold">
            <tr class="">
                <th scope="col">Imagem</th>
                <th scope="col">Código</th>
                <th scope="col">Item</th>
                <th scope="col">Entrega/Devolução</th>
                <th scope="col">Preço Unitário (R$)</th>
            </tr>
        </thead>

        <tbody class="arial12-font-normal">
            @foreach($pedido->itens as $item)
            <tr>
                <td><img src="{{ asset($item->produto?->fotoprincipal?->imagem->fullpatch()) }}"  width="50" height="50"></td>
                <td>{{$item->estoque?->codigo ?? 'Nenhum item do estoque'}}</td>
                <td>{{$item->produto->nome}} | {{$item->produto->marca}}</td>
                <td>
                    Entrega: {{ \Carbon\Carbon::parse($item->entrega->data_entrega)->format('d/m/Y') }} 
                    @if($pedido->tipo_frete == 'expresso')
                        às {{ \Carbon\Carbon::parse($item->entrega->hora_entrega_de)->format('H:i') }} 
                        até {{ \Carbon\Carbon::parse($item->entrega->hora_entrega_ate)->format('H:i') }}
                    @endif
                    @if($item->tipo_locacao == 'aluguel')
                    <br>Devolução: {{ \Carbon\Carbon::parse($item->entrega->data_devolucao)->format('d/m/Y') }} 
                    @if($pedido->tipo_frete == 'expresso')
                        às {{ \Carbon\Carbon::parse($item->entrega->hora_entrega_de)->format('H:i') }} 
                        até {{ \Carbon\Carbon::parse($item->entrega->hora_entrega_ate)->format('H:i') }}
                    @endif     
                    @endif
                </td>
                <td>RS {{getMoney($item->valor_unitario)}}</td>
                <!-- <td>{{getMoney($item->valor_total)}}</td> -->
            </tr>
            @endforeach
        </tbody>
    </table>
    <hr class="my-0 mb-1">
    <div class="row">
        <div class="col-10 text-end">
            Total dos itens ({{$pedido->itens->count()}})
        </div>
        <div class="col-2 text-end">
            RS{{getMoney($pedido->valor_total_produtos)}}
        </div>
    </div>
    <div class="row">
        <div class="col-10 text-end">
            Total do Pedido
        </div>
        <div class="col-2 text-end">
            RS{{getMoney($pedido->valor_total)}}
        </div>
    </div>
@endforeach      
</div>          
<div class="col-12 mx-auto justify-content-center mt-4">
    {{ $pedidos->links() }}
</div>

@php
    $categorias_qtds = $categorias_qtds ?? [];
    $categorias_total = $categorias_total ?? [];
@endphp

@foreach($pedidos_todos as $pedido)
    @foreach($pedido->itens as $item)
        @foreach($categorias as $categoria)
            @if ($item->produto->id_categoria == $categoria->id)
                @php
                    if (!isset($categorias_qtds[$categoria->nome])) {
                        $categorias_qtds[$categoria->nome] = 0;
                    }
                    if (!isset($categorias_total[$categoria->nome])) {
                        $categorias_total[$categoria->nome] = 0;
                    }

                    $categorias_qtds[$categoria->nome] += $item->qtd;
                    $categorias_total[$categoria->nome] += $item->valor_total;
                @endphp
            @endif
        @endforeach
    @endforeach
@endforeach
<div class="border px-0" style="width:50%">
    <div class="bg-dark arial14-font text-light text-bold w-100 ps-4 p-2">RESUMO DE ITENS</div>
    <table class="table display nowrap" id="clientes" style="width:100%">

        <thead>
            <tr class="">
                <th>Categoria do item</th>
                <th>Qtd. Total</th>
                <th>Valor Total Itens</th>
            </tr>
        </thead>
        <tbody class="arial12-font-normal">
            @foreach($categorias as $categoria)
                @php
                    $qtd = $categorias_qtds[$categoria->nome] ?? 0;
                    $total = $categorias_total[$categoria->nome] ?? 0;
                @endphp

                @if ($qtd != 0)
                <tr>
                    <td>{{ $categoria->nome }}</td>
                    <td>{{ $qtd }}</td>
                    <td>{{ getMoney($total) }}</td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>
    