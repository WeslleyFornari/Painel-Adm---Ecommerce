<select class="form-select" name="id_produto">
    <option value="">Selecione</option>
    @foreach($selecioneProdutos as $produto)
    <option value="{{$produto->id}}">{{ $produto->nome}}</option>
    @endforeach
</select>