
<div class="table-responsive">
    <table class="table display nowrap" id="logisticaItem" style="width:100%">
        <thead class="bg-dark arial14-font text-light text-bold">
            <tr>
                <th>Pedido</th>
                <th>Tipo Logística</th>
                <th>Data da Entrega/Devolução</th>
                <th>Situação</th>
            </tr>
        </thead>

        <tbody class="arial12-font-normal">
            @foreach ($entregas as $entrega)
            <tr style="border-bottom: #fff;">
                <td>{{$entrega->pedido->numero}}<br>{{$entrega->pedido->cliente?->name}}<br>({{$entrega->pedido->status->nome}})</td>
                <td>
                    @if ($entrega->pedido->tipo_frete == 'retirar_loja')
                    Retirar na Loja
                    <br><strong>Unidade de Retirada e Devolução</strong><br>{{$entrega->pedido->retirada?->nome_franquia}}
                    @elseif($entrega->pedido->tipo_frete == 'economico')
                    Frete(Econômico)
                    <br><strong>Endereço de Entrega</strong><br>CEP: {{$entrega->pedido->endereco_entrega?->cep}}<br>{{$entrega->pedido->endereco_entrega?->endereco}} , {{$entrega->pedido->endereco_entrega?->numero}} - {{$entrega->pedido->endereco_entrega?->cidade}}/{{$entrega->pedido->endereco_entrega?->estado}}
                    @elseif($entrega->pedido->tipo_frete == 'expresso')
                    Frete(Expresso)
                    <br><strong>Endereço de Entrega</strong><br>CEP: {{$entrega->pedido->endereco_entrega?->cep}}<br>{{$entrega->pedido->endereco_entrega?->endereco}} , {{$entrega->pedido->endereco_entrega?->numero}} - {{$entrega->pedido->endereco_entrega?->cidade}}/{{$entrega->pedido->endereco_entrega?->estado}}
                    @endif
                </td>
                <td>
                    @php
                    $entregaData = 0;
                    $devolucaoData = 0;
                    @endphp

                    @foreach ($entrega->pedido->itens as $item)
                    @if ($entregaData != $item->entrega->data_entrega || $devolucaoData != $item->entrega->data_devolucao)
                    Entrega: <br>
                    {{ \Carbon\Carbon::parse($item->entrega->data_entrega)->format('d/m/Y') }}</strong>
                    @if ($entrega->pedido->tipo_frete == 'expresso')
                    <strong>| {{ \Carbon\Carbon::parse($item->entrega->hora_entrega_de)->format('H:i') }}
                        às {{ \Carbon\Carbon::parse($item->entrega->hora_entrega_ate)->format('H:i') }} </strong>
                    @endif
                    <strong>({{ ucfirst(\Carbon\Carbon::parse($item->entrega->data_entrega)->locale('pt_BR')->isoFormat('dddd')) }})</strong>
                    <br>
                    Devolução: <br>
                    {{ \Carbon\Carbon::parse($item->entrega->data_devolucao)->format('d/m/Y') }}</strong>
                    @if ($entrega->pedido->tipo_frete == 'expresso')
                    <strong>| {{ \Carbon\Carbon::parse($item->entrega->hora_devolucao_de)->format('H:i') }}
                        às {{ \Carbon\Carbon::parse($item->entrega->hora_devolucao_ate)->format('H:i') }} </strong>
                    @endif
                    <strong>({{ ucfirst(\Carbon\Carbon::parse($item->entrega->data_devolucao)->locale('pt_BR')->isoFormat('dddd')) }})</strong>
                    @php
                    $entregaData = $item->entrega->data_entrega;
                    $devolucaoData = $item->entrega->data_devolucao;
                    @endphp
                    @endif
                    @endforeach
                </td>
                <td>
                    @php
                    $entregaData = 0;
                    $devolucaoData = 0;
                    @endphp

                    @foreach ($entrega->pedido->itens as $item)
                    @php
                    if ($item->entrega->status != 'entregue' && $item->entrega->status != 'devolvido' && $item->entrega->data_entrega < \Carbon\Carbon::now()->toDateString()){
                        $situacao = 'Entrega';
                        $statusSituacao = 'Pendente';
                        $cor = 'orange';
                        }
                        elseif ($item->entrega->status == 'entregue'){
                        $situacao = 'Entrega';
                        $statusSituacao = 'Concluído';
                        $cor = 'green';
                        }
                        elseif ($item->entrega->status == 'entregue' && $item->entrega->status != 'devolvido' && $item->entrega->data_devolucao < \Carbon\Carbon::now()->toDateString()){
                            $situacao = 'Devolução';
                            $statusSituacao = 'Pendente';
                            $cor = 'orange';
                            }
                            elseif ($item->entrega->status == 'devolvido'){
                            $situacao = 'Devolução';
                            $statusSituacao = 'Concluído';
                            $cor = 'green';
                            }
                            @endphp
                            @if ($entregaData != $item->entrega->data_entrega || $devolucaoData != $item->entrega->data_devolucao)
                            $situacao<br>
                            <div style="color: $cor">$statusSituacao</div>
                            @php
                            $entregaData = $item->entrega->data_entrega;
                            $devolucaoData = $item->entrega->data_devolucao;
                            @endphp
                            @endif
                            @endforeach
                </td>
            </tr>
            <tr>
                <td colspan="4" style="padding: 0;">
                    <table style="width: 100%;">
                        <thead>
                            <tr style="background-color: #e9ecef;">
                                <th colspan="3" style="text-align: center; padding: 0.25rem;">LISTA DE ITENS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($entrega->pedido->itens as $item)
                            <tr>
                                <td><img src="{{ asset($item->produto?->fotoprincipal?->imagem->fullpatch()) }}" style="height:60px; width:60px;"></td>
                                <td>{{$item->produto?->nome}} | {{$item->produto?->marca}}</td>
                                <td>{{$item->qtd}}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="2" style="text-align: right; padding-right:10px;">TOTAL DE ITENS</td>
                                <td>{{$entrega->pedido->itens->sum('qtd')}}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="col-12 mx-auto justify-content-center mt-4">
    {{ $entregas->links() }}
<div>
            