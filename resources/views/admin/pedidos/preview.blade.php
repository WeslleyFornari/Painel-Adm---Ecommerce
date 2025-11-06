<h5>Pedido Nº: {{$pedido->numero}}</h5>

<hr>

<div class="row">
    <h5>Cliente</h5>
    <div class="col-6 mt-2">
        <strong>Nome</strong><br>
        {{$pedido->cliente?->name}}
    </div>
    <div class="col-6 mt-2">
        <strong>Email</strong><br>
        {{$pedido->cliente?->email}}
    </div>
    <div class="col-6 mt-2">
        <strong>Telefone</strong><br>
        {{$pedido->cliente->dados?->celular}}<br>
        {{$pedido->cliente?->dados?->telefone}}
    </div>
    <div class="col-6 mt-2">
        <strong>CPF</strong><br>
        {{$pedido->cliente?->dados?->cpf}}<br>
    </div>
</div>
<hr>
<h5>Itens Pedido: {{$pedido->itens->count()}} itens</h5>

<div class="container mt-3">

    <div class="row text-600 text-white bg-primary py-25">
        <div class="col-3">Produto</div>
        <div class="col-2 text-center">Qtd</div>
        <div class="col-2 text-center">Valor</div>
        <div class="col-2 text-center">Total</div>
        <div class="col-2 text-center">Tempo</div>
    </div>

    @foreach ($pedido->itens as $k => $value)
    <div class="text-95 text-secondary-d3">
        <div class="row mb-2 mb-sm-0 py-25 border-bottom">
            <div class="col-3">{{$value->produto->nome}}</div>
            <div class="col-2 text-center">{{$value->qtd}}</div>
            <div class="col-2 text-center">R$ {{ getMoney($value->valor_unitario) }}</div>
            <div class="col-2 text-center">R$ {{ getMoney($value->valor_total) }}</div>
            <div class="col-2 text-center">{{entrega($value->id_entrega_pedido)}} dias</div>
        </div>
    </div>
    @endforeach
    <div class="row mt-3 justify-content-end">
            <div class="col-12 col-sm-5 text-grey text-90 order-first order-sm-last">

                <div class="row my-2">
                    <div class="col-7 text-end">
                        Sub Total
                    </div>
                    <div class="col-5 text-end">
                        <span class=" text-end ">{{ getMoney($pedido->valor_total,'R$') }}</span>
                    </div>
                </div>

                <div class="row my-2">
                    <div class="col-7  text-end">
                        Desconto
                    </div>
                    <div class="col-5 text-end text-danger ">
                        <span class=" text-danger-d1 ">-{{ getMoney($pedido->valor_desconto,'R$') }}</span>
                    </div>
                </div>

                <div class="row my-2 align-items-center bgc-primary-l3 ">
                    <div class="col-7 text-dark text-end">
                        <strong> Total</strong>
                    </div>
                    <div class="col-5 text-end text-dark">
                    <strong>{{ getMoney($pedido->valor_final,'R$') }}</strong>
                    </div>
                </div>
            </div>
    </div>
</div>

