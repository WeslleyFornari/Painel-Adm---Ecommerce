<div class="col-8">
    <div>Produtos Disponíveis</div>
    @php
    $availableProductIds = !empty($availableProducts) ? array_column($availableProducts, 'produto_id') : [];
    @endphp

    <select class="form-select produto select2" name="produtos[id_produto][]" id="produto-{{$cont}}" data-cont="{{$cont}}">
        <option value="0" selected>Selecione a Item</option>
        @foreach($produtos as $produto)
        @if(empty($availableProductIds) || in_array($produto->id, $availableProductIds))
        <option value="{{ $produto->id }}">{{ $produto->nome }}</option>
        @endif
        @endforeach
    </select>
</div>
<div class="col-sm-2" id="textQtd-{{$cont}}" style="display: none">
    <div>Qtd.</div>
    <div class="number-input justify-content-center">
        <button class="menos" type="button" onclick="menos({{$cont}})">
            <i class="fas fa-minus"></i>
        </button>
        <input type="number" id="qtd-{{$cont}}" value="1" name="item[qtd][]" />
        <button class="mais" type="button" onclick="mais({{$cont}})">
            <i class="fas fa-plus"></i>
        </button>
    </div>
</div>
<div class="col-12">
    <div>Valores</div>
    <div class="row">
        <div class="col-6">
            <label for="">Valor Unitário</label>
        </div>
        <div class="col-6 d-flex">
            <span class="mt-2" style="color: #87BEEF">R$</span>
            <input type="text" class="form-control moneyMask valor_uni" name="item[valor_unitario][]" style="color: #87BEEF" id="inputvalorUni-{{$cont}}" data-cont="{{$cont}}">
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <label for="">Valor Total</label>
        </div>
        <div class="col-6 d-flex">
            <span class="total mt-2">R$</span>
            <input type="text" class="form-control moneyMask valor_total" name="item[valor_total][]" id="valortotal-{{$cont}}">
        </div>
    </div>
    <div class="row" id="">
        <div class="col-12">
            <div class="form-group col-sm-12 mt-3 observacoes-container" id="obsContainer-{{$cont}}" style="display: none;">
                <div class="row">
                    <div class="form-group col-sm-6">
                        <span class="titulo">Observações Internas:</span>
                        <textarea name="observacoes_internas" id="observacoes_internas" class="form-control" rows="4" placeholder="Observações visíveis apenas para administradores"></textarea>
                    </div>
                    <div class="form-group col-sm-6">
                        <span class="titulo">Observações Cliente:</span>
                        <textarea name="observacoes_cliente" id="observacoes_cliente" class="form-control" rows="4" placeholder="Observações que serão visíveis para o cliente"></textarea>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>