
<div class="row  arial12-font">

        <div class="col-1 text-center">FOTO</div>
        <div class="col-4 ">ITEM</div>
        <div class="col-2 ">ESTOQUE</div>
        <div class="col-1 text-center">QTD</div>
        <div class="col-2 text-end">PREÇO UNID (R$)</div>
        <div class="col-2 text-end">TOTAL (R$)</div>
        <!-- <div class="col-2 text-center">REPOSIÇÂO (R$)</div> -->

</div>
<hr>


@foreach ($pedido->itens as $k => $value)
<div class="row py-2 my-1 arial12-font-normal">

        <div class="col-1 "><img src="{{ asset($value->produto?->fotoprincipal?->imagem->fullpatch()) }}" style="height:60px; width:60px;"></div>
        <div class="col-4 ">{{$value->produto->nome}}</div>
        <div class="col-2 "  @if (!$value->id_item_estoque) style="display:none" @endif id="estoque-{{$value->id}}">
                <div class="row">
                        <div class="col-6" id="estoqueText-{{$value->id}}">{{$value->estoque?->codigo}}</div>
                        <button class="btn btn-danger btn-sm col-5 mx-0 px-0" onclick="mudarEstoque({{ $value->id }})">mudar</button>
                </div>
        </div>
        <div class="col-2 " @if ($value->id_item_estoque) style="display:none" @endif id="estoqueSelect-{{$value->id}}">
                <select class="form-select estoque" name="id_estoque" data-item="{{$value->id}}">
                        <option value="">Selecione Item</option>
                        @foreach (estoque_disponivel($pedido->id, $value->id) as $estoque)
                                <option value="{{$estoque->id}}">{{$estoque->codigo}}</option>
                        @endforeach
                </select>
                <button class="btn btn-danger btn-sm col-5 mx-0 px-0 mt-2" onclick="cancelarMudar({{ $value->id }})" style="display:none" id="cancelar-{{$value->id}}">cancelar</button>
        </div>
        <div class="col-1 text-center">{{$value->qtd}}</div>
        <div class="col-2 text-end">R$ {{ getMoney($value->valor_unitario ?? '00.00') }}</div>
        <div class="col-2 text-end">R$ R$ {{ getMoney((intval($value->qtd) ?? 0) * ($value->valor_unitario ?? 0)) }}</div>
        <!-- <div class="col-2 text-center">0,00</div> -->

</div>
<hr>
@endforeach

<div class="row  py-2 my-1 arial12-font">

        <div class="col-1 "></div>
        <div class="col-4 "></div>
        <div class="col-2 "></div>
        <div class="col-1 "></div>
        <div class="col-2 text-end">SUBTOTAL (R$)</div>
        <div class="col-2 text-end">{{ getMoney($pedido->valor_total_produtos ?? '00.00') }}</div>
        <!-- <div class="col-2 "></div> -->
</div>
<hr>

<div class="row  py-2 my-1 arial12-font">

        <div class="col-1 "></div>
        <div class="col-4 "></div>
        <div class="col-2 "></div>
        <div class="col-1 "></div>
        <div class="col-2 text-end">CUPOM</div>
        <div class="col-2 text-end">{{$pedido->cupom?->codigo ?? 'Não tem cupom'}}</div>
        <!-- <div class="col-2 "></div> -->
</div>
<hr>

<!-- <div class="row  py-2 my-1 arial12-font" style="color:green;">

        <div class="col-1 "></div>
        <div class="col-4 "></div>
        <div class="col-1 "></div>
        <div class="col-2 text-end">DESCONTO (%)</div>
        <div class="col-2 text-end">{{$pedido->cupom?->valor}}%</div>
        <div class="col-2 "></div>
</div>
<hr> -->

<div class="row  py-2 my-1 arial12-font" style="color:green;">

        <div class="col-1 "></div>
        <div class="col-4 "></div>
        <div class="col-2 "></div>
        <div class="col-1 "></div>
        <div class="col-2 text-end">DESCONTO (R$)</div>
        <div class="col-2 text-end">{{getMoney($pedido->valor_desconto ?? '00.00')}}
        </div>
        <!-- <div class="col-2 "></div> -->
</div>
<hr>

<div class="row  py-2 my-1 arial12-font">

        <div class="col-1 "></div>
        <div class="col-4 "></div>
        <div class="col-2 "></div>
        <div class="col-1 "></div>
        <div class="col-2 text-end">FRETE (R$)</div>
        <div class="col-2 text-end">{{getMoney($pedido->valor_frete  ?? '00.00')}}</div>
        <!-- <div class="col-2 "></div> -->
</div>
<hr>

<!-- <div class="row  py-2 my-1 arial12-font" style="color:red;">

        <div class="col-1 "></div>
        <div class="col-3 "></div>
        <div class="col-2 "></div>
        <div class="col-2 text-end">VALOR EXTRA (R$)</div>
        <div class="col-2 text-end">X,XX</div>
        <div class="col-2 "></div>
</div>
<hr> -->

<div class="row  py-2 my-1 arial16-font" style="color:black;">

        <div class="col-1 "></div>
        <div class="col-4 "></div>
        <div class="col-2 "></div>
        <div class="col-1 "></div>
        <div class="col-2 text-end">TOTAL GERAL (R$)</div>
        <div class="col-2 text-end">{{getMoney($pedido->valor_total_produtos + $pedido->valor_frete - $pedido->valor_desconto  ?? '00.00')}}</div>
        <!-- <div class="col-2 "></div> -->
</div>
<hr>
@if ($pedido->pagamento == 'parcial' && $pedido->id_status != '3')
    <div class="row py-2 my-1 arial16-font text-end" style="color:black;">
        <p><strong>PAGAMENTO PARCIAL</strong></p>
        <p style="color:green;">Valor já pago: <strong>R$ {{ getMoney($pedido->valor_liquido) }}</strong></p>
        <p style="color:red;">Saldo a pagar: R$ {{ getMoney($pedido->valor_total_produtos + $pedido->valor_frete - ($pedido->valor_desconto ?? 0) - $pedido->valor_liquido) }}</p>
    </div>
@endif


