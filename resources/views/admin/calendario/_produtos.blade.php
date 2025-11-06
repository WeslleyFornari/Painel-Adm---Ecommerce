<div>Produtos</div>
<select class="form-select" name="produto" id="produtos">
    <option value="">Selecione o Produto</option>
    @foreach($produtos as $produto)
        <option value="{{ $produto->id }}">{{ $produto->nome }}</option>
    @endforeach
</select>