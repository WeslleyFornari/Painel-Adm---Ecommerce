<select name="bairro" class="form-select" id="bairro">
    <option value="">Selecione Bairro</option>
    @foreach($bairros as $bairro)
        <option value="{{$bairro->id}}">{{$bairro->bairro}}</option>
    @endforeach
</select> 