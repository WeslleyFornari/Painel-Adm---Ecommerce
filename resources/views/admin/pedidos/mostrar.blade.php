@extends('layouts.app')


@section('assets')

<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />

<style>
    .imprimir{
        position: absolute;
        bottom: 60px;
        background: #fff;
        border: 1px solid #bbb;
        right: 0;
        text-align: left;
        border-radius: 10px;
    }
    .imprimir .borda{
        display: block;
        width: 100%;
        border-bottom: 1px solid #bbb;
        padding: 0.5rem;
    }
    .sub-titulo{
        font-weight: 700;
    }
</style>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
   
    <div class="row mb-3">
        <div class="col-7 ps-4 my-2">
            <h5>Pedido de locação #{{$pedido->numero}}</h5>          
        </div>
    </div>

                    <div class="card">
                    <div class="card-body">
                        
                        <div class="row">
                                
                                <div class="col-sm-12"><a href="#infoGeral" data-bs-toggle="collapse" class="titulo ms-3">INFORMAÇÕES GERAIS</a></div> 

                                <div class="collapse" id="infoGeral">
                                    <div class="row mt-1 mx-2 pt-3">
                                            @include('admin.pedidos.include._informacoes-gerais')
                                    </div>
                                </div>

                        </div>
                        <hr>

                        <div class="row">

                                <div class="col-sm-12"><a href="#infoLocacao" data-bs-toggle="collapse" class="titulo ms-3">INFORMAÇÕES DE LOCAÇÃO</a></div> 

                                <div class="collapse" id="infoLocacao">
                                    <div class="row mt-1 mx-2 pt-3 bordered-row">
                                            @include('admin.pedidos.include._informacoes-locacao')
                                    </div>
                                </div>

                        </div>
                         <hr>

                        <div class="row">

                                <div class="col-sm-12"><a href="#infoLogistica" data-bs-toggle="collapse" class="titulo ms-3">INFORMAÇÕES DE LOGISTICA</a></div> 

                                <div class="collapse" id="infoLogistica">
                                    <div class="row mt-1 mx-2 pt-3 bordered-row">
                                            @include('admin.pedidos.include._informacoes-logistica')
                                    </div>
                                </div>

                        </div>
                        <hr>

                        <!-- <div class="row">

                                <div class="col-sm-12"><a href="#" data-bs-toggle="collapse" class="titulo ms-3">DETALHES PARA TRANSPORTE</a></div> 

                                <div class="collapse" id="#">
                                    <div class="row mt-1 mx-2 pt-3 bordered-row">
                                           
                                    </div>
                                </div>

                        </div>
                        <hr> -->

                        <div class="row mb">
                            <div class="col-sm-12 bg-dark text-center arial12-font text-light text-bold m-0 py-1 justify-content-center mb-4">TABELA DE ITENS</div>
                           
                                @include('admin.pedidos.include._itens')
                           
                        </div>
                       
                        <!-- <div class="row">
                            <div class="col-sm-12 titulo">INFORMAÇÕES COMPLEMENTARES (mais detalhes)</div>


                        </div>
                        <hr> -->

                        <div class="row justify-content-end">
                            <input type="hidden" class="form-control" id="link" value="{{$pedido->url()}}">
                            <div class="col-3 text-end me-3 position-relative">
                                <div class="collapse imprimir" id="collapseExample">
                                    <a class="borda" href="{{route('admin.pdf.pdfEntrega', $pedido->id)}}">Checklist Entrega</a>
                                    <a class="borda" href="{{route('admin.pdf.pdfDevolucao', $pedido->id)}}">Checklist Devolução</a>
                                    <a class="p-2 d-block" href="{{route('admin.pdf.pdfEntregaDevolucao', $pedido->id)}}">Checklist Entrega e Devolução</a>
                                </div>
                                <button class="btn btn-danger" type="button" id="copyButton" onclick="copyText()">
                                    Link Pagamento
                                </button>
                                <button class="btn btn-light collapseExample" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                    IMPRIMIR
                                </button>
                            </div>
                        </div>
                    </div>
                    </div>
    </div>
</div>


@endsection


@section('scripts')
<script>
    $("body").on('change', '.estoque', function (e) { 
        e.preventDefault();
        var id = $(this).val();
        var item = $(this).data('item');
        console.log(id);

        var url = '{{ route("admin.pedidos.mudarItens", ["id" => ":id", "idItem" => ":idItem"]) }}';
        url = url.replace(':id', id).replace(':idItem', item);

        $.get(url, function(data) {
            var estoque = document.querySelector('#estoqueText-' + item);
            estoque.innerHTML = `${data.codigo}`;
            $('#estoqueSelect-' + item).hide();
            $('#estoque-' + item).show();
        });
    });
    $("body").on('click', '.collapseExample', function (e) { 
        e.preventDefault();
        $('#collapseExample').toggle();
    });

    function mudarEstoque(item){
        $('#estoque-' + item).hide();
        $('#estoqueSelect-' + item).show();
        $('#cancelar-' + item).show();

    }

    function mudarUnidade(){
        $('#mudarUnidade').hide();
        $('#cancelarUnidade').show();
        $('#unidade').prop('disabled', false);
    }
    function cancelarMudarUnidade(){
        $('#mudarUnidade').show();
        $('#cancelarUnidade').hide();
        $('#unidade').prop('disabled', true);
    }

    $("body").on('change', '.retirada', function (e) { 
        e.preventDefault();
        var unidade = $(this).val();
        var id = '{{$pedido->id}}';
        console.log(id);

        var url = '{{ route("admin.pedidos.mudarUnidade", ["id" => ":id", "idFranquia" => ":idFranquia"]) }}';
        url = url.replace(':id', id).replace(':idFranquia', unidade);

        $.get(url, function(data) {
            $('#mudarUnidade').show();
            $('#cancelarUnidade').hide();
            $('#unidade').prop('disabled', true);
        });
    });

    function cancelarMudar(item){
        $('#estoqueSelect-' + item).hide();
        $('#estoque-' + item).show();
    }

    function copyText() {
        var copyText = document.getElementById("link");
        copyText.select();
        navigator.clipboard.writeText(copyText.value).then(function() {
            var successMessage = document.createElement('span');
            successMessage.className = 'text-end';
            successMessage.style.color = 'green';
            successMessage.textContent = 'Texto copiado com sucesso';
            copyText.after(successMessage);
            setTimeout(function() {
                successMessage.remove();
            }, 3000);
        })
    }


</script>
@endsection