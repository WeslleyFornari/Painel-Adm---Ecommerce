
@if(isset($cliente))
<form action="{{route('admin.clientes.update', $cliente->id)}}" class="formStoreclientes">
    @csrf
    <div class="row mt-4">
            <div class="col-12 col-sm-5">
                <label for="">Nome * </label>
                <input type="text"  name="name" required class="form-control" value="{{$cliente->name}}">
                
            </div>
            <div class="col-12 col-sm-4 ">
                    <label for="">Email *</label>
                <input type="email" name="email" id="email" class="form-control" value="{{$cliente->email}}"required>
            </div>   
            
        </div>


    <!-- <div class="row mt-3">

        <div class="col-12 col-sm-3 ">
            <label for="senha" class="form-label" >Senha:</label>
            <input type="password" class="form-control" id="senha" name="password">    
        </div>
    
        <div class="col-12 col-sm-3">
            <label for="confirmar_senha" class="form-label">Confirmar Senha:</label>
            <input type="password" class="form-control" id="confirmar_senha" name="password_confirmation">
        </div>

    </div> -->
    

    <div class="row mt-4 border-top pt-4">
        <div class="col">
            <a href="{{route('admin.clientes.index')}}" class="btn btn-primary">Voltar</a>
        </div>
        <div class="col text-end">
            <button class="btn btn-success" type="submit">Salvar</button>
        </div>
    </div>
</form>

@else

<form action="{{route('admin.clientes.store')}}" class="formStoreclientes">
@csrf 
    <div class="row mt-4">
            <div class="col-12 col-sm-5">
                <label for="">Nome * </label>
                <input type="text"  name="name" required class="form-control">
                
            </div>
            <div class="col-12 col-sm-4 ">
                    <label for="">Email *</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>   
            
        </div>

        

    <!-- <div class="row mt-3">

        <div class="col-12 col-sm-3 ">
            <label for="senha" class="form-label" >Senha:</label>
            <input type="password" class="form-control" id="senha" name="password" required>    
        </div>
    
        <div class="col-12 col-sm-3">
            <label for="confirmar_senha" class="form-label">Confirmar Senha:</label>
            <input type="password" class="form-control" id="confirmar_senha" name="password_confirmation" required>
        </div>

    </div> -->
    

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