
@if(isset($usuario))
<form action="{{route('admin.usuarios.update', $usuario->id)}}" class="formStoreUsuarios">
    @csrf
    <div class="row mt-4">
            <div class="col-12 col-sm-5">
                <label for="">Nome * </label>
                <input type="text"  name="name" required class="form-control" value="{{$usuario->name}}">
                
            </div>
            <div class="col-12 col-sm-4 ">
                    <label for="">Email *</label>
                <input type="email" name="email" id="email" class="form-control" value="{{$usuario->email}}"required>
            </div>   

            <div class="col-12 col-sm-2">
                <label for="">Perfil * </label>

                <select class="form-select role"  name="role" required>
                    <option value="">Selecione</option>
                    <option value="admin" @if($usuario->role == 'admin') selected @endif>Administrador</option>
                    <option value="franqueado" @if($usuario->role == 'franqueado') selected @endif>Franqueado</option>
                    
                    
                </select>
            </div>

           
            <div class="form-group col-sm-4"  @if ($usuario->role != 'franqueado') style="display:none" @endif id="franquia">
                <label for=""> Franquias*: </label>
                <select class="form-select" name="id_franquia">
                    <option value="">Selecione</option>
                    @foreach($selecionarFranquia as $franquias)
                        <option value="{{$franquias->id}}" @if($franquias->id == $usuario->id_franquia) selected @endif>{{ $franquias->nome_franquia}}</option>
                    @endforeach
                </select>
            </div>
            
        </div>


    <div class="row mt-3">

        <div class="col-12 col-sm-3 ">
            <label for="senha" class="form-label" >Senha:</label>
            <input type="password" class="form-control" id="senha" name="password">    
        </div>
    
        <div class="col-12 col-sm-3">
            <label for="confirmar_senha" class="form-label">Confirmar Senha:</label>
            <input type="password" class="form-control" id="confirmar_senha" name="password_confirmation">
        </div>

    </div>
    

    <div class="row mt-4 border-top pt-4">
        <div class="col">
            <a href="{{route('admin.usuarios.index')}}" class="btn btn-primary">Voltar</a>
        </div>
        <div class="col text-end">
            <button class="btn btn-success" type="submit">Salvar</button>
        </div>
    </div>
</form>

@else

<form action="{{route('admin.usuarios.store')}}" class="formStoreUsuarios">
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

            <div class="col-12 col-sm-2">
                <label for="">Perfil * </label>

                <select class="form-select role"  name="role" required>
                    <option value="">Selecione</option>
                    <option value="admin">Administrador</option>
                    <option value="franqueado">Franqueado</option>
                    <!-- <option value="user">Usuário</option> -->
                </select>
            </div>
            <div class="form-group col-sm-4" style="display:none" id="franquia">
                <label for=""> Franquias*: </label>
                <select class="form-select" name="id_franquia">
                    <option value="">Selecione</option>
                    @foreach($selecionarFranquia as $franquias)
                        <option value="{{$franquias->id}}">{{ $franquias->nome_franquia}}</option>
                    @endforeach
                </select>
            </div>
            
        </div>

        

    <div class="row mt-3">

        <div class="col-12 col-sm-3 ">
            <label for="senha" class="form-label" >Senha:</label>
            <input type="password" class="form-control" id="senha" name="password" required>    
        </div>
    
        <div class="col-12 col-sm-3">
            <label for="confirmar_senha" class="form-label">Confirmar Senha:</label>
            <input type="password" class="form-control" id="confirmar_senha" name="password_confirmation" required>
        </div>

    </div>
    

    <div class="row mt-4 border-top pt-4">
        <div class="col">
            <a href="{{route('admin.usuarios.index')}}" class="btn btn-primary">Voltar</a>
        </div>
        <div class="col text-end">
            <button class="btn btn-success" type="submit">Salvar</button>
        </div>
    </div>
</form>
@endif