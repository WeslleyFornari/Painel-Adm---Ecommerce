
<hr>
<div class="col-6">
    <div class="row">
    <p> <span class="sub-titulo"> <strong>Cliente:</strong></span> <span class="corpo">{{$pedido->cliente?->name}}</span></p>
    <p> <span class="sub-titulo"> <strong>CPF/CNPJ:</strong></span> <span class="corpo">{{$pedido->cliente?->dados?->cpf}}</span></p>
    <p> <span class="sub-titulo"><strong>Email:</strong></span> <span class="corpo"> {{$pedido->cliente?->email}}</span></p>
    <p> <span class="sub-titulo"><strong>Celular:</strong></span> <span class="corpo"> <a href="https://api.whatsapp.com/send?phone={{ formatPhoneNumber($pedido->cliente?->dados?->celular)}}" target="_blank">
    {{$pedido->cliente?->dados?->celular}}
    </a></span></p>


    </div>
</div>



<div class="col-6">
    <div class="justify-content-end row text-start">

            <!-- <div class="col-6">
                <p class="sub-titulo">Atendente</p>
                <p class="corpo">Natiele Cecilia da Silva</p>
                <p class="sub-titulo">Modelo de Contrato</p>
                <p class="corpo">Modelo Padrão de Locação</p>
            </div> -->

            <div class="col-6">
                <p class="sub-titulo"><strong>Data do Pedido</strong></p>
                <p class="corpo">{{$pedido->created_at->format('d/m/Y H:i:s')}}</p>
                <!-- <p class="sub-titulo">Data da Confirmação</p>
                <p class="corpo">xx/XX/xxxx</p> -->
            </div>

    </div>
</div>