<div class="col-12 my-2">
    <h4>Produtos Relacionadas</h4>  
    @if($produto)
    @foreach ($produto->produtos_relacionados as $prod_relac)
    <div class="col-12 mt-2 row">
        <div class="col-3">
            <span class="titulo"> Nome: *</span>
            <input type="text" name="prod_relac[nome][]" value="{{$prod_relac->nome}}" class="form-control" required>
        </div>
        <div class="col-3">
            <span class="titulo"> Marca: *</span>
            <input type="text" name="prod_relac[marca][]" value="{{$prod_relac->marca}}" class="form-control" required>
        </div>
        <div class="col-3">
            <span class="titulo"> Valor: *</span>
            <input type="text" name="prod_relac[valor][]" value="{{$prod_relac->valor_base_diaria}}" class="form-control moneyMask" required>
        </div>
        <div class="col-6">
            <span class="titulo"> Descrição: *</span>
            <input type="text" name="prod_relac[descricao][]" value="{{$prod_relac->descricao}}" class="form-control" required>
        </div>
        <div class="col-3">
        <span class="titulo"></span><br>
        <a href="#" class="btn btn-icon-only btn-danger close" onclick="excluirProd_relac(this)"><i class="fas fa-trash"></i></a>
        </div>
    </div>
    @endforeach   
    @endif   
    <div class="" id="prod_relacview"></div>
    <a href="" class="btn btn-sm btn-primary adicionarprod_relac" style="font-size: 15px"><i class="fas fa-plus-circle" style="font-size: 15px"></i> Adicionar Produto</a>     
</div>