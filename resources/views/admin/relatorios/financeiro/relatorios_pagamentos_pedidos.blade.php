
              <div class="table-responsive">
                            <table class="table display nowrap" id="Financeiro" style="width:100%">

                                <thead class="bg-dark arial14-font text-light text-bold">
                                    <tr class="">
                                        <th scope="col">Pedido</th>
                                        <th scope="col">Cliente</th>
                                        <th scope="col">Data do Pedido</th>
                                        <th scope="col">CPF/CNPJ</th>
                                        <th scope="col">Situação do Pedido</th>
                                        <th scope="col">Valor frete</th>
                                        <th scope="col">Valor Total</th>
                                        <th scope="col">Forma de PGTO</th>
                                        <th scope="col">Situação PGTO</th>
                                        <th scope="col">Valor Bruto</th>
                                        <th scope="col">Valor Líquido</th>
                                        
                                    </tr>
                                </thead>

                                <tbody class="arial12-font-normal">
                                    @foreach($pedidos as $k => $ped)
                                        <tr>   
                                            <td> <a href="{{route('admin.pedidos.preview', $ped->id)}}" target="_blank">{{ $ped->numero }}</a></td>
                                            <td>{{$ped->cliente?->name}}</td>
                                            <td>  {{$ped->created_at->format('d/m/Y H:i') ?? ''}}</td>
                                            <td>  {{ $ped->cliente?->cpf ?? $ped->cliente?->cnpj ?? '' }}</td>
                                            <td>  {{$ped->status->nome}}</td>
                                            <td>  {{getMoney($ped->valor_frete) ?? ''}}</td>
                                            <td>  {{getMoney($ped->valor_total) ?? ''}}</td>
                                            <td>  {{$ped->forma_pagamento->nome ?? ''}}</td>
                                            <td>  
                                                @if ($ped->id_status <= '2')
                                                <span style="color: orange">Pendente</span>
                                                @else
                                                <span style="color: green">Pago</span>
                                                @endif
                                            </td>
                                            <td> R$ {{getMoney($ped->valor_total) ?? ''}}</td>
                                            <td> R$ {{getMoney($ped->valor_liquido) ?? ''}}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>Total de Pedido (com frete)</td>
                                        <td>{{ getMoney($pedidos->sum('valor_total')) }}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>Total de Pedido (sem frete)</td>
                                        <td>{{ getMoney($pedidos->sum('valor_total_produtos')) }}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>Total de Pagamentos Realizados (Bruto)</td>
                                        <td>{{ getMoney($pedidosRealizados->sum('valor_total')) }}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>Total de Pagamentos Realizados (Líquido)</td>
                                        <td>{{ getMoney($pedidosRealizados->sum('valor_liquido')) }}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>Total de Pagamentos Pendentes (Líquido)</td>
                                        <td>{{ getMoney($pedidosPendentes->sum('valor_liquido')) }}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>Valor Total Frete</td>
                                        <td>{{ getMoney($pedidos->sum('valor_frete')) }}</td>
                                    </tr>
                                
                                </tbody>
                            </table>

                        </div> 
                        <div class="col-12 justify-content-center mt-4">
                     {{ $pedidos->links() }}
                </div>
                
     