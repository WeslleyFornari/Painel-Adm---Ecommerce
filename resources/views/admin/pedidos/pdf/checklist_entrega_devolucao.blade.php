<!-- resources/views/checklist.blade.php -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checklist de Entrega</title>
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"> -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .bordar{
            border: 0.15rem solid black;
        }
        p{
            font-size: 11px;
            margin: 0.25rem;
        }

        hr{
            border-bottom: 1px solid #000;
            width: 100%;
            margin-top: 1rem;
            margin-bottom: 1rem;
        }
        .entrega hr{
            border-bottom: 1px solid #000;
            width: 100%;
            margin-top: 1rem;
            margin-bottom: 0.25rem;
        }
        .my-3 {
            margin-bottom: 1rem !important;
            margin-top: 1rem !important;
        }
        h5 {
            font-size: 1.25rem;
        }
        .mt-2, .my-2 {
            margin-top: .5rem !important;
        }
        .align-content-center {
            -ms-flex-line-pack: center !important;
            align-content: center !important;
        }

        .flex-wrap {
            -ms-flex-wrap: wrap !important;
            flex-wrap: wrap !important;
        }
        strong {
            font-weight: bolder;
        }
        .px-0 {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        .pl-2, .px-2 {
    padding-left: .5rem !important;
}
.pr-2, .px-2 {
    padding-right: .5rem !important;
}
.justify-content-center {
    -ms-flex-pack: center !important;
    justify-content: center !important;
}

.justify-content-end {
    -ms-flex-pack: end !important;
    justify-content: flex-end !important;
}
.w-100{
    width: 100%;
}
.px-4 {
    padding-left: 1.5rem !important;
    padding-right: 1.5rem !important;
}

tr{
    font-size: 11px;
    border-bottom: 1px solid rgba(0, 0, 0, .125);
}
    </style>
</head>
<body>
    <div class="page">
        <div>
            <table style="width:100%; border: 2px solid #000;">
                <tbody>
                    <tr>
                        <td style="padding:4px; width: 10%;">
                            @if($pedido->franquia?->tipo_franqueado == 'trip')
                            <img src="https://facilitrip.dvelopers.com.br/assets/img_site/logo.svg" alt="" style="width: 100px;">
                            @else
                            <img src="https://facilitoy.facilitrip.com.br/assets/img_facilitoy/logo.png" alt="" style="width: 100px;">
                            @endif
                        </td>
                        <td style="border-right: 2px solid #000; padding:10px 5px;  width: 70%;">
                            
                            <div class="pl-3">
                                @if($pedido->franquia?->tipo_franqueado == 'trip')
                                    <p><strong>Facilitrip</strong><br>
                                @else
                                    <p><strong>Facilitoy</strong><br>
                                @endif
                                <strong style="text-transform: uppercase;">{{$pedido?->franquia?->nome_franquia}}</strong><br>
                                CNPJ: <strong>{{$pedido?->franquia?->cnpj}}</strong><br>
                                {{$pedido?->franquia?->celular}} / {{$pedido?->franquia?->telefone}}<br>
                                <!-- <a href="https://sao-paulo.facilitoy.com.br/">https://sao-paulo.facilitoy.com.br/</a><br> -->
                                <strong>{{$pedido?->franquia?->endereco}}, {{$pedido?->franquia?->numero}} - {{$pedido?->franquia?->bairro}}. <br> 
                                {{$pedido?->franquia?->cidade}}/{{$pedido?->franquia?->estado}} - CEP: {{$pedido?->franquia?->cep}}</strong></p>
                            </div>
                        </td>
                        <td style="padding: 10px 40px; width: 30%; ">
                            <p style="text-align: center">PEDIDO</p>
                            <p style="text-align: center">{{$pedido->numero}}</p>
                            <img src="{{ $fileUrl }}" alt="QR Code" style="width: 100%; height: auto;">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h5 class="my-3" style="text-align:center">Checklist de Entrega</h5>

        <div class="mt-2">
            <table style="width:100%; border-collapse:collapse; border: 1px solid rgba(0, 0, 0, .125);">
                <thead>
                    <tr>
                        <th colspan="4" style="text-align:center; padding:4px;"><strong>Informações de logística</strong></th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid rgba(0, 0, 0, .125);">
                        <td style="border-right: 1px solid rgba(0, 0, 0, .125); padding:4px; width: 16%;">Cliente</td>
                        <td style="border-right: 1px solid rgba(0, 0, 0, .125); padding:4px; width: 40%;"><strong>{{$pedido?->cliente?->name}}</strong></td>
                        <td style="border-right: 1px solid rgba(0, 0, 0, .125); padding:4px; width: 16%;">Início da Locação</td>
                        <td style="padding:4px;"><strong>{{ \Carbon\Carbon::parse($data_entrega)->format('d/m/Y') }}</strong></td>
                    </tr>
                    <tr style="border-top: none; border-bottom: 1px solid rgba(0, 0, 0, .125);">
                        <td style="border-right: 1px solid rgba(0, 0, 0, .125); padding:4px; width: 16%;">Telefone(s)</td>
                        <td style="border-right: 1px solid rgba(0, 0, 0, .125); padding:4px; width: 40%;"><strong>@if($pedido?->cliente?->dados?->telefone){{$pedido?->cliente?->dados?->telefone}}/ @endif{{$pedido?->cliente?->dados?->celular}}</strong></td>
                        <td style="border-right: 1px solid rgba(0, 0, 0, .125); padding:4px; width: 16%;">Término da Locação</td>
                        <td style="padding:4px;"><strong>{{ \Carbon\Carbon::parse($data_devolucao)->format('d/m/Y') }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

                

        <div class="mt-2">
            <table style="width:100%; border-collapse:collapse; border: 1px solid rgba(0, 0, 0, .125);">
                <tbody>
                    <tr style="border-bottom: 1px solid rgba(0, 0, 0, .125);">
                        @if ($pedido-> id_endereco_entrega != $pedido->id_endereco_devolucao)
                        <td style="border-right: 1px solid rgba(0, 0, 0, .125); padding:4px; width: 16%;">Endereço da Entrega</td>
                        @else
                            <td style="border-right: 1px solid rgba(0, 0, 0, .125); padding:4px; width: 16%;">Endereço da Entrega e Devolução</td>
                        @endif
                        <td style="padding:4px; width: 84%;">
                            Local: <strong>{{$pedido?->endereco_entrega?->endereco}}, {{$pedido?->endereco_entrega?->numero}}</strong><br>
                            CEP: <strong>{{$pedido?->endereco_entrega?->cep}}</strong><br>
                            <strong>{{$pedido?->endereco_entrega?->bairro}}, {{$pedido?->endereco_entrega?->cidade}} - {{$pedido?->endereco_entrega?->estado}}</strong><br>
                            Complemento: <strong>{{$pedido?->endereco_entrega?->complemento}}</strong><br>
                            Telefone(s): <strong>{{$pedido->telefone_receber}}</strong>
                        </td>
                    </tr>
                </tbody>
            </table>
            @if ($pedido-> id_endereco_entrega != $pedido->id_endereco_devolucao)
            <table style="width:100%; border-collapse:collapse; border: 1px solid rgba(0, 0, 0, .125); border-top: none;">
                <tbody>
                    <tr style="border-bottom: 1px solid rgba(0, 0, 0, .125);">
                        <td style="border-right: 1px solid rgba(0, 0, 0, .125); padding:4px; width: 16%;">Endereço da Devolução</td>
                        <td style="padding:4px; width: 84%;">
                            Local: <strong>{{$pedido?->endereco_devolucao?->endereco}}, {{$pedido?->endereco_devolucao?->numero}}</strong><br>
                            CEP: <strong>{{$pedido?->endereco_devolucao?->cep}}</strong><br>
                            <strong>{{$pedido?->endereco_devolucao?->bairro}}, {{$pedido?->endereco_devolucao?->cidade}} - {{$pedido?->endereco_devolucao?->estado}}</strong><br>
                            Complemento: <strong>{{$pedido?->endereco_devolucao?->complemento}}</strong><br>
                            Telefone(s): <strong>{{$pedido->telefone_receber}}</strong>
                        </td>
                    </tr>
                </tbody>
            </table>
            @endif
            @php
                $entregaData = 0;
                $devolucaoData = 0; 
            @endphp
            @foreach ($pedido->itens as $item)
                @if ($entregaData != $item?->entrega?->data_entrega || $devolucaoData != $item?->entrega?->data_devolucao)
                <table style="width:100%; border-collapse:collapse; border: 1px solid rgba(0, 0, 0, .125); border-top: none">
                    <tbody>
                        <tr style="border-top: none; border-bottom: 1px solid rgba(0, 0, 0, .125);">
                            <td style="border-right: 1px solid rgba(0, 0, 0, .125); padding:4px; width: 15.6%;">Entrega</td>
                            <td style="border-right: 1px solid rgba(0, 0, 0, .125); padding:4px; width: 33%;">
                                <table style="width: 100%; ">
                                    <tr>
                                        <td style="width: 20%;">
                                            <strong>{{ \Carbon\Carbon::parse($item?->entrega?->data_entrega)->format('d/m/Y') }}</strong>
                                        </td>
                                        @if ($pedido->tipo_frete == 'economico')
                                        <td style="width: 40%; padding-right: 0.25rem; padding-left: 0.25rem">
                                            <hr style="margin-bottom: 0.25rem">
                                        </td>
                                        @elseif ($pedido->tipo_frete == 'expresso')
                                        <td style="width: 35%;">
                                        <strong>{{ \Carbon\Carbon::parse($item?->entrega?->hora_entrega_de)->format('H:i') }} 
                                            às {{ \Carbon\Carbon::parse($item?->entrega?->hora_entrega_ate)->format('H:i') }} </strong>
                                        </td>
                                        @endif
                                        <td>
                                            <strong>({{ ucfirst(\Carbon\Carbon::parse($item?->entrega?->data_entrega)->locale('pt_BR')->isoFormat('dddd')) }})</strong>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td style="border-right: 1px solid rgba(0, 0, 0, .125); padding:4px; width: 16%;">Devolução</td>
                            <td style="padding:4px; width: 33%;">
                                <table style="width: 100%; ">
                                    <tr>
                                        <td style="width: 20%;">
                                            <strong>{{ \Carbon\Carbon::parse($item?->entrega?->data_devolucao)->format('d/m/Y') }}</strong>
                                        </td>
                                        @if ($pedido->tipo_frete == 'economico')
                                        <td style="width: 40%; padding-right: 0.25rem; padding-left: 0.25rem">
                                            <hr style="margin-bottom: 0.25rem">
                                        </td>
                                        @elseif ($pedido->tipo_frete == 'expresso')
                                        <td style="width: 35%;">
                                        <strong>{{ \Carbon\Carbon::parse($item?->entrega?->hora_devolucao_de)->format('H:i') }} 
                                            às {{ \Carbon\Carbon::parse($item?->entrega?->hora_devolucao_ate)->format('H:i') }} </strong>
                                        </td>
                                        @endif
                                        <td>
                                            <strong>({{ ucfirst(\Carbon\Carbon::parse($item?->entrega?->data_devolucao)->locale('pt_BR')->isoFormat('dddd')) }})</strong>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        </tbody>
                </table>
                @endif
                @php
                $entregaData = $item?->entrega?->data_entrega;
                $devolucaoData = $item?->entrega?->data_devolucao; 
                @endphp
            @endforeach
        </div>

        <div class="mt-2">
            <table style="width:100%; border-collapse:collapse; border: 1px solid rgba(0, 0, 0, .125);">
                <thead>
                    <tr style="border-bottom: 1px solid rgba(0, 0, 0, .125);">
                        <th style="border-right: 1px solid rgba(0, 0, 0, .125); padding:4px; text-align: left; width: 40%;"><strong>Item</strong></th>
                        <th style="border-right: 1px solid rgba(0, 0, 0, .125); padding:4px; text-align: left; width: 5%;"><strong>Qtd</strong></th>
                        <th style="border-right: 1px solid rgba(0, 0, 0, .125); padding:4px; text-align: left"><strong>Separado</strong></th>
                        <th style="border-right: 1px solid rgba(0, 0, 0, .125); padding:4px; text-align: left"><strong>Conferido</strong></th>
                        <th style="border-right: 1px solid rgba(0, 0, 0, .125); padding:4px; text-align: left"><strong>Entregue</strong></th>
                        <th style="border-right: 1px solid rgba(0, 0, 0, .125); padding:4px; text-align: left"><strong>Coletado</strong></th>
                        <th style="padding:4px;"><strong>Devolvido</strong></th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($pedido->itens as $item)
                    <tr style="border-bottom: 1px solid rgba(0, 0, 0, .125);">
                        <td style="border-right: 1px solid rgba(0, 0, 0, .125); padding: 4px; text-align: center;">
                            <table style="width: 100%; ">
                                <tr>
                                    <td style="width: 80px;">
                                        <img src="{{$url}}{{ $item->produto->fotoprincipal ? $item->produto->fotoprincipal->imagem->file : '' }}" alt="" style="max-width: 100%; max-height: 80px;">
                                    </td>
                                    <td style="text-align: left;">
                                        {{$item?->estoque?->codigo}} - {{$item?->produto?->nome}} / {{$item?->produto?->marca}}
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="border-right: 1px solid rgba(0, 0, 0, .125); padding:4px; text-align:center;">{{$item->qtd}}</td>
                        <td style="border-right: 1px solid rgba(0, 0, 0, .125); padding:4px; text-align:center;">[ <strong style="color: #fff">X</strong> ]</td>
                        <td style="border-right: 1px solid rgba(0, 0, 0, .125); padding:4px; text-align:center;">[ <strong style="color: #fff">X</strong> ]</td>
                        <td style="border-right: 1px solid rgba(0, 0, 0, .125); padding:4px; text-align:center;">[ <strong style="color: #fff">X</strong> ]</td>
                        <td style="border-right: 1px solid rgba(0, 0, 0, .125); padding:4px; text-align:center;">[ <strong style="color: #fff">X</strong> ]</td>
                        <td style="padding:4px; text-align:center;">[ <strong style="color: #fff">X</strong> ]</td>
                    </tr>
                @endforeach
                    <!-- <tr style="border-top: none; border-bottom: 1px solid rgba(0, 0, 0, .125); width: 100%;">
                        <td style="border-right: 1px solid rgba(0, 0, 0, .125); padding:4px; text-align:right; width: 40%;"><strong>QTD TOTAL DE ITENS</strong></td>
                        <td style="border-right: 1px solid rgba(0, 0, 0, .125); padding:4px; text-align:center;"><strong>{{$pedido->itens->sum('qtd')}}</strong></td>
                        <td style="padding:4px;" colspan="3"></td>
                    </tr> -->
                </tbody>
            </table>
            <table style="width:100%; border-collapse:collapse; border: 1px solid rgba(0, 0, 0, .125); border-top: none">
                <tbody>
                    <tr style="border-top: none; border-bottom: 1px solid rgba(0, 0, 0, .125);">
                        <td style="border-right: 1px solid rgba(0, 0, 0, .125); padding:4px; text-align:right; width: 40%;"><strong>QTD TOTAL DE ITENS</strong></td>
                        <td style="border-right: 1px solid rgba(0, 0, 0, .125); padding:4px; text-align:center; width: 5%;"><strong>{{$pedido?->itens?->sum('qtd')}}</strong></td>
                        <td style="padding:4px;" colspan="3"></td> 
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-2">
            <table style="width: 100%; ">
                <tr>
                    <td style="width: 50%">
                        <table style="width:100%; border-collapse:collapse; border: 1px solid rgba(0, 0, 0, .125);">
                            <thead>
                                <tr style="border-bottom: 1px solid rgba(0, 0, 0, .125);">
                                    <th style="text-align:center; padding:4px;"><strong>Observações e Ocorrências Identificadas na Entrega</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="padding:10px;">
                                        <hr>
                                        <hr>
                                        <hr>
                                        <hr>
                                        <hr>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td style="width: 50%">
                        <table style="width:100%; border-collapse:collapse; border: 1px solid rgba(0, 0, 0, .125);">
                            <thead>
                                <tr style="border-bottom: 1px solid rgba(0, 0, 0, .125);">
                                    <th style="text-align:center; padding:4px;"><strong>Observações e Ocorrências Identificadas na Devolução</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="padding:10px;">
                                        <hr>
                                        <hr>
                                        <hr>
                                        <hr>
                                        <hr>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        <div class="mt-2 entrega">
            <table style="width: 100%; ">
                <tr>
                    <td style="width: 50%">
                        <table style="width:100%; border-collapse:collapse; border: 1px solid rgba(0, 0, 0, .125);">
                            <thead>
                                <tr style="border-bottom: 1px solid rgba(0, 0, 0, .125);">
                                    <th colspan="2" style="text-align:center; padding:4px;"><strong>Entrega</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="border-bottom: 1px solid rgba(0, 0, 0, .125);">
                                    <td style="border-right: 1px solid rgba(0, 0, 0, .125); padding:4px; width: 50%">Separado Por (Funcionário)</td>
                                    <td style="padding:4px;"><hr></td>
                                </tr>
                                <tr style="border-bottom: 1px solid rgba(0, 0, 0, .125);">
                                    <td style="border-right: 1px solid rgba(0, 0, 0, .125); padding:4px;  width: 50%">Conferido Por (Funcionário)</td>
                                    <td style="padding:4px;"><hr></td>
                                </tr>
                                <tr style="border-bottom: 1px solid rgba(0, 0, 0, .125);">
                                    <td style="border-right: 1px solid rgba(0, 0, 0, .125); padding:4px;  width: 50%">Assinatura do Cliente</td>
                                    <td style="padding:4px;"><hr></td>
                                </tr>
                                <tr style="border-bottom: 1px solid rgba(0, 0, 0, .125);">
                                    <td style="border-right: 1px solid rgba(0, 0, 0, .125); padding:4px;  width: 50%">RG/CPF do Cliente</td>
                                    <td style="padding:4px;"><hr></td>
                                </tr>
                                <tr style="width: 30%">
                                    <td style="border-right: 1px solid rgba(0, 0, 0, .125); padding:4px; width: 50%">Data de Entrega</td>
                                    <td style="padding:4px;"><hr></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td style="width: 50%">
                        <table style="width:100%; border-collapse:collapse; border: 1px solid rgba(0, 0, 0, .125);">
                            <thead>
                                <tr style="border-bottom: 1px solid rgba(0, 0, 0, .125);">
                                    <th colspan="2" style="text-align:center; padding:4px;"><strong>Entrega</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="border-bottom: 1px solid rgba(0, 0, 0, .125);">
                                    <td style="border-right: 1px solid rgba(0, 0, 0, .125); padding:4px; width: 50%">Coletado Por (Funcionário)</td>
                                    <td style="padding:4px;"><hr></td>
                                </tr>
                                <tr style="border-bottom: 1px solid rgba(0, 0, 0, .125);">
                                    <td style="border-right: 1px solid rgba(0, 0, 0, .125); padding:4px;  width: 50%">Conferido Por (Funcionário)</td>
                                    <td style="padding:4px;"><hr></td>
                                </tr>
                                <tr style="border-bottom: 1px solid rgba(0, 0, 0, .125);">
                                    <td style="border-right: 1px solid rgba(0, 0, 0, .125); padding:4px;  width: 50%">Assinatura do Cliente</td>
                                    <td style="padding:4px;"><hr></td>
                                </tr>
                                <tr style="border-bottom: 1px solid rgba(0, 0, 0, .125);">
                                    <td style="border-right: 1px solid rgba(0, 0, 0, .125); padding:4px;  width: 50%">RG/CPF do Cliente</td>
                                    <td style="padding:4px;"><hr></td>
                                </tr>
                                <tr style="width: 30%">
                                    <td style="border-right: 1px solid rgba(0, 0, 0, .125); padding:4px; width: 50%">Data da Devolução</td>
                                    <td style="padding:4px;"><hr></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

    </div>
</body>
</html>
