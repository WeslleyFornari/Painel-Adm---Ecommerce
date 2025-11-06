<p class="me-3">Produtos: </p>
<select class="form-select p-0 ps-2" id="produtoid">
    <option value="todos" selected>Todos</option>
    @foreach($selecionarProduto as $produto)
        <option value="{{$produto->id}}">{{ $produto->nome}}</option>
    @endforeach
</select>