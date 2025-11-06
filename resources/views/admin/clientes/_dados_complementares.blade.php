
@if(isset($cliente->dados))

<form action="{{route('admin.dados_clientes.update', $cliente->dados->id)}}" id="formStoreDadosClientes">
 @csrf 
    <div class="row mt-4">                                                                            
        <div class="col-12 col-sm-4">
            <label for="">CPF *</label>
            <input type="text" name="cpf" required class="form-control cpfMask" id="cpf" value="{{$cliente->dados->cpf ?? ''}}">
        </div>
        
        <div class="col-12 col-sm-4">
            <label for="">CNPJ</label>
            <input type="text" name="cnpj" class="form-control cnpjMask" id="cnpj" value="{{$cliente->dados->cnpj ?? ''}}">
            <small id="cnpj-error" style="color: red; display: none;">CNPJ inválido</small>
        </div>       
        <div class="col-12 col-sm-4">
            <label for="">Celular *</label>
            <input type="text" name="celular" required class="form-control phoneMask" value="{{$cliente->dados->celular ?? ''}}">
        </div>
        <div class="col-12 col-sm-4">
            <label for="">Telefone </label>
            <input type="text" name="telefone" class="form-control phoneMask" value="{{$cliente->dados->telefone ?? ''}}">
        </div>
        <div class="col-12 col-sm-4">
            <label for="">Data Nascimento </label>
            <input type="text" name="data_nascimento" class="form-control data_nascimento_flatpicker" value="{{$cliente->dados->data_nascimento ?? ''}}">
        </div>
    </div>

    <div class="row mt-4 border-top pt-4">
        <div class="col">
            <a href="{{route('admin.clientes.index')}}" class="btn btn-primary">Voltar</a>
        </div>
        <div class="col text-end">
            <button class="btn btn-success" type="submit">Salvar</button>
        </div>
    </div>
</form>
@endif