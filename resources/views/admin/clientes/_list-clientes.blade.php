
   
        <div class="row bg-dark arial16-font text-light text-bold m-0 py-2">

                        <div class="col-1">ID</div>
                        <div class="col-3">Nome</div>
                        <div class="col-2">Perfil</div>
                        <div class="col-3">Email</div>
                        <div class="col-1">Status</div>

                        <div class="col-2 text-center">Ações</div>                 
        </div>

                <!--BODY-->
                @foreach($clientes as $k => $item)
                <div class="row m-0 py-2 border-bottom arial14-font-normal align-items-center">

                    <div class="col-1">{{$item->id}}</div>
                     <div class="col-3">{{$item->name ?? ''}}</div>
                     <div class="col-2">{{$item->role ?? ''}}</div>
                     <div class="col-3">{{$item->email ?? ''}}</div>


                      <div class="col-1 justify-content-center d-flex">
                                <div class="form-check form-switch">
                                    <input class="form-check-input status-categoria"
                                        type="checkbox" name="status" role="switch" 
                                        value="ativo"
                                        data-id="{{$item->id}}"
                                        @if($item->status == 'ativo')
                                        checked
                                        @endif>
                                    <label class="form-check-label" for="flexSwitchCheckChecked">@if($item->status == 'ativo') Ativo @else Inativo @endif</label>
                                </div>
                            </div>

                         <div class="col-2 text-center">
                               <a href="{{ route('admin.clientes.preview',$item->id) }}"class="btn btn-info btn-sm show btn-icon-only preview-cliente"><i class="fas fa-eye"></i></a>
                                @if(permissao('clientes')->editar == 'sim')
                                    <a href="{{route('admin.clientes.edit', $item->id)}}" class="btn btn-sm btn-icon-only btn-secondary editar-clientes"> <i class="fas fa-pencil"></i> </a>
                                @endif
                                @if(permissao('clientes')->deletar == 'sim')
                                    <a href="{{route('admin.clientes.delete', $item->id) }}" class="btn btn-sm btn-icon-only btn-danger btn-exclude"> <i class="fas fa-trash"></i> </a>          
                                @endif
                        </div>   
                </div>
                @endforeach  
                <div class="row mt-5 justify-content-center">
                            <div class="col-sm-12 mx-auto align-center">
                            {!! $clientes->links() !!}
                            </div>
                        </div>

                
                

    
            
    
          


