
   
        <div class="row bg-dark arial16-font text-light text-bold m-0 py-2">

                       
                        <div class="col-2">Titulo</div>
                        <div class="col-1">Ordem</div> 
                        <div class="col-3">Url</div>
                        <div class="col-3">Nova Janela</div>
                        <div class="col-1">Status</div>

                        <div class="col-2 text-center">Ações</div>                 
        </div>

                <!--BODY-->
                @foreach($banners as $k => $banner)
                <div class="row m-0 py-2 border-bottom arial14-font-normal align-banners-center">
      
                     <div class="col-2">{{$banner->titulo ?? ''}} <br> <small>facili{{$banner->tipo_franqueado}}</small></div>
                     <div class="col-1">{{$banner->ordem ?? ''}}</div>
                     <div class="col-3">{{$banner->url ?? ''}}</div>
                     <div class="col-3">{{$banner->new_window ?? ''}}</div>


                     <div class="col-1 justify-content-center d-flex">
                                <div class="form-check form-switch">
                                    <input class="form-check-input status-categoria"
                                        type="checkbox" name="status" role="switch" 
                                        value="ativo"
                                        data-id="{{$banner->id}}"
                                        @if($banner->status == 'ativo')
                                        checked
                                        @endif>
                                    <label class="form-check-label" for="flexSwitchCheckChecked">@if($banner->status == 'ativo') Ativo @else Inativo @endif</label>
                                </div>
                            </div>

                         <div class="col-2 text-center">       
                                 @if(permissao('banners')->editar == 'sim')                
                                <a href="{{route('admin.banners.edit', $banner->id)}}" class="btn btn-sm btn-icon-only btn-secondary editar-banners"> <i class="fas fa-pencil"></i> </a>
                                @endif
                                @if(permissao('banners')->deletar == 'sim')
                                <a href="{{route('admin.banners.delete', $banner->id) }}" class="btn btn-sm btn-icon-only btn-danger btn-destroy"> <i class="fas fa-trash"></i> </a>          
                                @endif
                        </div>   
                </div>
                @endforeach  
                <div class="row mt-5 justify-content-center">
                            <div class="col-sm-12 mx-auto align-center">
                            {!!$banners->links()!!}
                            </div>
                        </div>

                
                

    
            
    
          


