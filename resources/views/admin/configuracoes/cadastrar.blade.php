@if(isset($configuracao))
<a href="#" class="tooglegeCollapse float-end" data-target="#collapse-Config2"><i class="fas fa-times"></i></a>

<!--FORMULARIO EDITAR-->
    <form action="{{route('admin.configuracoes.update',['id'=>$configuracao->id])}}" id="atualizar-configuracoes" enctype="multipart/form-data">
            @csrf
            <div class="row mt-2 arial12-font">
           
                <div class="form-group col-sm-4">
                <span class="titulo"> Tipo: *</span>          
                    <select name="tipo_franqueado" required id="" class="form-select">
                       
                        <option value="toy" @if($configuracao->tipo_franqueado == 'toy') selected @endif>TOY</option>
                        <option value="trip" @if($configuracao->tipo_franqueado == 'trip') selected @endif>TRIP</option>
                    </select>
               </div>    
              
                <div class="form-group col-sm-8">
                    <span class="titulo"> Titulo: *</span>          
                    <input type="text" name="titulo" value="{{$configuracao->titulo ?? ''}}"   class="form-control" required>  
                </div>

                <div class="form-group col-sm-8">
                    <span class="titulo"> Valor: *</span>          
                    <input type="text" name="value" value="{{$configuracao->value ?? ''}}"  class="form-control" required>  
                </div>


                    <div class="col-sm-4 text-center mt-3"> 
                        <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> Atualizar</button>
                    </div>
            </div>
    </form>

@else

<a href="#" class="tooglegeCollapse float-end" data-target="#collapse-Config"><i class="fas fa-times"></i></a>

<!--FORMULARIO CADASTRO-->
        <form id="cadastrar-configuracoes" enctype="multipart/form-data">
        @csrf
        <div class="row mt-2 arial12-font">

          
            <div class="form-group col-sm-4">
                <span class="titulo"> Tipo: *</span>          
                <select name="tipo_franqueado" required id="" class="form-select">
                    <option value="">Selecione</option>
                    <option value="toy">TOY</option>
                    <option value="trip">TRIP</option>
                </select>
            </div>    
            <div class="form-group col-sm-8">
                    <span class="titulo"> Titulo: *</span>          
                    <input type="text" name="titulo"  class="form-control" required>  
            </div>
         
            <div class="form-group col-sm-8">
                    <span class="titulo"> Valor: *</span>          
                    <input type="text" name="value" class="form-control" required>  
            </div>
            <div class="col-sm-3 mt-3 text-center"> 
                        <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> Salvar</button>
            </div>
            
        </div>         
        
        </form>
@endif        


  