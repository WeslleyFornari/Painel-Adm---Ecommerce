<div class="col-12 my-2">
    <h4>Promoções</h4>  
    @if($produto)
    @php
        $cont = 0;
    @endphp
    @foreach ($produto->promocoes as $promocoes)
    <div class="col-12 mt-2 row vds">
        <div class="col-2">
            <span class="titulo"> De:</span>
            <input type="text" name="promocoes[de][]" value="{{$promocoes->de}}" class="form-control" >
        </div>
        <div class="col-2">
            <span class="titulo"> Até:</span>
            <input type="text" name="promocoes[ate][]" value="{{$promocoes->ate}}" class="form-control" >
        </div>
        <div class="col-2">
            <span class="titulo"> Tipo: </span>
            <select class="form-select" name="promocoes[tipo][]" id="tipo_desconto_{{$cont}}" data-cont="{{$cont}}">
                <option value="">Selecione</option>
                <option value="porcentagem" @if ($promocoes->tipo == 'porcentagem') selected @endif>Porcentagem (%)</option>
                <option value="real" @if ($promocoes->tipo == 'real') selected @endif>Real (R$)</option>
            </select>
        </div>
        <div class="col-2">
            <span class="titulo"> Desconto:</span>
            <input type="text" name="promocoes[desconto][]" value="{{$promocoes->desconto}}" class="form-control" >
        </div>
        <div class="col-2">
        <span class="titulo"></span><br>
        <a href="#" class="btn btn-icon-only btn-danger close" onclick="excluirPromocoes(this)"><i class="fas fa-trash" aria-hidden="true"></i></a>
        </div>
    </div>
    @php
        $cont++;
    @endphp
    @endforeach   
    @endif   
    <div class="" id="promocoesview"></div>
    <a href="" class="btn btn-sm btn-primary adicionarPromocoes" style="font-size: 15px"><i class="fas fa-plus-circle" style="font-size: 15px"></i> Adicionar Promoções</a>     
</div>