<div>Estoque</div>
<select class="form-select" name="estoque" id="estoques">
    <option value="">Selecione o Estoque</option>
    @foreach($estoques as $estoque)
        <option value="{{ $estoque->id }}">{{ $estoque->codigo }}</option>
    @endforeach
</select>