 

<div class="table-responsive">
    <table class="table display nowrap" id="clientes" style="width:100%">

        <thead class="bg-dark arial14-font text-light text-bold">
            <tr class="">
                @if(Auth::user()->role != 'franqueado')
                <th scope="col">Franquia</th>
                @endif
                <th scope="col">Pedido</th>
                <th scope="col">Data do Pedido</th>
                <th scope="col">Entrega</th>
                <th scope="col">Situação</th>
                <th scope="col">Itens</th>
                <th scope="col">Descontos</th>
                <th scope="col">Frete</th>
                <th scope="col">Total Pago</th>
                <th scope="col">Total Pendente</th>
                <th scope="col">Total</th>
            </tr>
        </thead>

        <tbody class="arial12-font-normal">
            @foreach($pedidos as $k => $ped)
            <tr>
                @if(Auth::user()->role != 'franqueado')
                <td class="text-uppercase">
                    {{$ped->franquia->tipo_franqueado}}<br>
                    {{$ped->franquia->nome_franquia}}
                </td>
                @endif
                <td> 
                    {{$ped->cliente?->name}}<br>
                    {{$ped->cliente?->dados?->cpf}}<br>
                    <a href="{{route('admin.pedidos.preview', $ped->id)}}" target="_blank">{{ $ped->numero }}</a></td>
                <td> {{$ped->created_at->format('d/m/Y H:i') ?? ''}}</td>
                <td>
                    @foreach (itens_pedidos($ped) as $item)
                        {{ \Carbon\Carbon::parse($item['data_entrega'])->format('d/m/Y') }}</strong>
                        @if ($ped->tipo_frete == 'expresso')
                            <strong>| {{ \Carbon\Carbon::parse($item['hora_entrega_de'])->format('H:i') }}
                            às {{ \Carbon\Carbon::parse($item['hora_entrega_ate'])->format('H:i') }} </strong>
                        @endif
                        @if ($item['status'] != 'entregue' && $item['status'] != 'devolvido' && $item['data_entrega'] < \Carbon\Carbon::now()->toDateString())
                            <div style="color: orange">Pendente</div>
                        @elseif($item['status'] == 'entregue')
                            <div style="color: green">Entregue</div>
                        @else
                            <div style="color: orange">Pendente</div>
                        @endif
                    @endforeach
                </td>
                <td>{{$ped->status->nome}}</td>
                <td> R$ {{getMoney($ped->valor_total_produtos ?? '00.00')}}</td>
                <td> R$ -{{getMoney($ped->valor_desconto ?? '00.00')}}</td>
                <td>
                    R$ {{getMoney($ped->valor_frete ?? '00.00')}}<br>
                    {{$ped->frete()}}
                </td>
                <td> R$ {{getMoney($ped->valor_liquido) ?? '00.00'}}</td>
                <td>
                    @if($ped->pagamento == 'parcial')
                    R$ {{ getMoney($ped->valor_total_produtos + $ped->valor_frete - $ped->valor_liquido - $ped->valor_desconto) }}
                    @else
                    R$ 00,00
                    @endif
                </td>
                <td> R$ {{getMoney($ped->valor_total) ?? ''}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="col-12 mx-auto justify-content-center mt-4">
    {{ $pedidos->links() }}
</div>
      