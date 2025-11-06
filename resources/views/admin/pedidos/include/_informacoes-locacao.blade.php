

<hr>
<div class="col-4">
    <div class="row">

    <p class="sub-titulo">Inicio Locação</p>
    <p class="corpo">{{ \Carbon\Carbon::parse($data_entrega)->format('d/m/Y') }}</p>
   
    </div>
</div>


<div class="col-4">
    <div class="row text-start">

        <p class="sub-titulo">Término Locação</p>
        <p class="corpo">{{ \Carbon\Carbon::parse($data_devolucao)->format('d/m/Y') }}</p>
               

    </div>
</div>


<div class="col-4">
    <div class="row text-start">
        <p class="sub-titulo">Objetivo da Locação</p>
        <p class="corpo">dia a dia</p>
    </div>
</div>

<div class="col-4">
<div class="row text-start">
        <p class="sub-titulo">Observações internas</p>
        <p class="corpo">{{$pedido->observacoes_internas ?? 'Nenhuma observação foi feita'}}</p>
    </div>
</div>
<div class="col-4">
<div class="row text-start">
        <p class="sub-titulo">Observações para cliente</p>
        <p class="corpo">{{$pedido->observacoes_cliente ?? 'Nenhuma observação foi feita'}}</p>
    </div>
</div>

<!-- <hr> -->
<!-- <div class="col-8">
    <div class="row text-start mt-2">

        <p class="sub-titulo">Tipo da Locação</p>
        <p class="corpo">Por Periodo</p>
        <p class="corpo">* Esta informação impacta diretamente no calculo sobre os itens do pedido, onde por padrão é realizado <b>Por Periodo</b></strong>
           Caso selecionado <strong>Por diaria</strong>, será realiazado o calculo em meio aos dias de locação do pedido pelo valor unitario da cada item
        </p>

    </div>
</div> -->

