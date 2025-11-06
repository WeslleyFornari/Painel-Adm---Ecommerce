
<div class="col-6">
@php
    $entregaData = 0;
    $devolucaoData = 0; 
@endphp
@foreach ($pedido->itens as $item)
    @if ($entregaData != $item->entrega->data_entrega || $devolucaoData != $item->entrega->data_devolucao)


        <div class="row">
            <div class="col-6">
                <p class="sub-titulo">Data da Entrega</p>
                <p class="corpo">{{ \Carbon\Carbon::parse($item->entrega->data_entrega)->format('d/m/Y') }}</p>
                <p class="sub-titulo">Data de Devolução</p>
                <p class="corpo">{{ \Carbon\Carbon::parse($item->entrega->data_devolucao)->format('d/m/Y') }}</p>
            </div>
            <div class="col-6">
                @if($pedido->tipo_frete == 'expresso')
                <p class="sub-titulo">Hora da Entrega</p>
                <p class="corpo">{{ \Carbon\Carbon::parse($item->entrega->hora_entrega_de)->format('H:i') }} 
                às {{ \Carbon\Carbon::parse($item->entrega->hora_entrega_ate)->format('H:i') }}</p>
                <p class="sub-titulo">Hora da Devolução</p>
                <p class="corpo">{{ \Carbon\Carbon::parse($item->entrega->hora_devolucao_de)->format('H:i') }} 
                às {{ \Carbon\Carbon::parse($item->entrega->hora_devolucao_ate)->format('H:i') }}</p>
                @endif
            </div>
        </div>
        <hr>
    @endif
    @php
    $entregaData = $item->entrega->data_entrega;
    $devolucaoData = $item->entrega->data_devolucao; 
    @endphp
@endforeach
<div class="col-12 corpo">
        * Esta programação de datas será utilizada no controle de estoque de locação
    </div>

    <div class="col-sm-12 titulo mt-4 mb-2">CONFIGURAÇÕES DE LOGISTICA</a></div> 

    <div class="col-12">
        <p class="sub-titulo">Tipo de logistica</p>
        <p class="corpo">{{ucfirst($pedido->tipo_frete ?? '')}}</p>
    </div>
    <div class="col-12">
        <p class="sub-titulo">Taxa de Frete (R$)</p>
        <p class="corpo">R${{getMoney($pedido->valor_frete ?? '00.00')}}</p>
    </div>
</div>

@if ($pedido->tipo_frete == 'retirar_loja')

<div class="col-6">
    <h5>Unidade para Entrega/Devolução</h5>
    <div class="row">
        <div class="col-6">
            <select name="id_retirada" id="unidade" class="form-select retirada" @if ($pedido->id_retirada) disabled @endif>
                <option value="">Selecionar a Unidade</option>
                @foreach ($franquias as $franquia)
                <option value="{{$franquia->id}}" @if ($franquia->id == $pedido->id_retirada) selected @endif>{{$franquia->nome_franquia}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6">
            <button class="btn btn-danger btn-sm col-5 mx-0 px-0" onclick="mudarUnidade()" @if (!$pedido->id_retirada) style="display:none" @endif id="mudarUnidade">mudar</button>
            <button class="btn btn-danger btn-sm col-5 mx-0 px-0 mt-2" onclick="cancelarMudarUnidade()" style="display:none" id="cancelarUnidade">cancelar</button>
        </div>
    </div>
</div>

@else

<div class="col-6">
    <div class="row">
        @if($pedido->id_endereco_entrega != $pedido->id_endereco_devolucao)
        <h5>Endereço Entrega</h5>
        @else
        <h5>Endereço Entrega/Devolucao</h5>
        @endif
        <div class="col-4">
            <p class="sub-titulo">Apelido</p>
            <p class="corpo">{{$pedido->endereco_entrega?->apelido}}</p>
        </div>
        <div class="col-6">
            <p class="sub-titulo">CEP</p>
            <p class="corpo">{{$pedido->endereco_entrega?->cep}}</p>
        </div>
        <div class="col-6">
            <p class="sub-titulo">Endereço</p>
            <p class="corpo">{{$pedido->endereco_entrega?->endereco}}</p>
        </div>
        <div class="col-3">
            <p class="sub-titulo">Numero</p>
            <p class="corpo">{{$pedido->endereco_entrega?->numero}}</p>
        </div>
        <div class="col-3">
            <p class="sub-titulo">Complemento</p>
            <p class="corpo">{{$pedido->endereco_entrega?->complemento}}</p>
        </div>
        <div class="col-4">
            <p class="sub-titulo">Bairro</p>
            <p class="corpo">{{$pedido->endereco_entrega?->bairro}}</p>
        </div>
        <div class="col-4">
            <p class="sub-titulo">Cidade</p>
            <p class="corpo">{{$pedido->endereco_entrega?->cidade}}</p>
        </div>
        <div class="col-4">
            <p class="sub-titulo">Estado</p>
            <p class="corpo">{{$pedido->endereco_entrega?->estado}}</p>
        </div>
    </div>

    @if($pedido->id_endereco_entrega != $pedido->id_endereco_devolucao)

    <div class="row mt-3">
        <h5>Endereço Devolução</h5>
        <div class="col-4">
            <p class="sub-titulo">Apelido</p>
            <p class="corpo">{{$pedido->endereco_devolucao?->apelido}}</p>
        </div>
        <div class="col-6">
            <p class="sub-titulo">CEP</p>
            <p class="corpo">{{$pedido->endereco_devolucao?->cep}}</p>
        </div>
        <div class="col-6">
            <p class="sub-titulo">Endereço</p>
            <p class="corpo">{{$pedido->endereco_devolucao?->endereco}}</p>
        </div>
        <div class="col-3">
            <p class="sub-titulo">Numero</p>
            <p class="corpo">{{$pedido->endereco_devolucao?->numero}}</p>
        </div>
        <div class="col-3">
            <p class="sub-titulo">Complemento</p>
            <p class="corpo">{{$pedido->endereco_devolucao?->complemento}}</p>
        </div>
        <div class="col-4">
            <p class="sub-titulo">Bairro</p>
            <p class="corpo">{{$pedido->endereco_devolucao?->bairro}}</p>
        </div>
        <div class="col-4">
            <p class="sub-titulo">Cidade</p>
            <p class="corpo">{{$pedido->endereco_devolucao?->cidade}}</p>
        </div>
        <div class="col-4">
            <p class="sub-titulo">Estado</p>
            <p class="corpo">{{$pedido->endereco_devolucao?->estado}}</p>
        </div>
    </div>
    @endif

    <div class="row mt-3">
        <div class="col-6">
            <p class="sub-titulo">Nome de quem vai receber</p>
            <p class="corpo">{{$pedido->nome_receber}}</p>
        </div>
        <div class="col-6">
            <p class="sub-titulo">Telefone de quem vai receber</p>
            <p class="corpo">{{$pedido->telefone_receber}}</p>
        </div>
    </div>
</div>

@endif