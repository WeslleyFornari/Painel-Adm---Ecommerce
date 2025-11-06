
   
        <div class="row bg-dark arial16-font text-light text-bold m-0 py-2">

                       
                        <div class="col-1">Foto</div>
                        <div class="col-6">Depoimentos</div> 
                        <div class="col-2 text-center">Tipo</div>
                        <div class="col-1">Status</div>

                        <div class="col-2 text-center">Ações</div>                 
        </div>

                <!--BODY-->
                @foreach($depoimentos as $k => $depoimento)
                <div class="row m-0 py-2 border-bottom arial14-font-normal align-depoimentos-center">

                                       
                     <div class="col-1"> 
                      @if ($depoimento->id_foto)
                      <img height="50" width="50" src="{{asset('uploads/' . $depoimento->imagem?->file)}}" class="rounded-circle"></div>
                      @else
                      <img height="50" width="50" src="{{asset('assets/img/mae.png')}}" class="rounded-circle" style="background: #e9f2fc"></div>
                      @endif
                     <div class="col-6">{!! \Illuminate\Support\Str::limit(strip_tags($depoimento->texto), 108, '...') !!}</div>
                     <div class="col-2 text-center">{{$depoimento->tipo_franqueado ?? ''}}</div>

                     <!-- TOGGLE SWITCH -->
                    @if($depoimento->status == 'ativo')
                          <div class="col-1 justify-content-center">
                              <label class="switch">
                                <input type="checkbox" checked class="status-depoimento" data-id="{{$depoimento->id}}">
                                <span class="slider round"></span>
                              </label>
                             
                              <strong style="display:inline; ">Ativo</strong>
                        </div>
                     @else    
                           <div class="col-1 justify-content-center">
                              <label class="switch">
                                <input type="checkbox" class="status-depoimento" data-id="{{$depoimento->id}}">
                                <span class="slider round"></span>
                              </label>

                              <strong style="display:inline; ">Inativo</strong>
                            </div> 
                    @endif

                         <div class="col-2 text-center">        
                            @if(permissao('depoimentos')->editar == 'sim')               
                                <a href="{{route('admin.depoimentos.edit', $depoimento->id)}}" class="btn btn-sm btn-icon-only btn-secondary editar-depoimentos"> <i class="fas fa-pencil"></i> </a>
                            @endif
                            @if(permissao('depoimentos')->deletar == 'sim')
                                <a href="{{route('admin.depoimentos.delete', $depoimento->id) }}" class="btn btn-sm btn-icon-only btn-danger btn-destroy"> <i class="fas fa-trash"></i> </a>          
                            @endif
                        </div>   
                </div>
                @endforeach  
                <div class="row mt-5 justify-content-center">
                            <div class="col-sm-12 mx-auto align-center">
                            {!!$depoimentos->links()!!}
                            </div>
                        </div>

                
                

    
            
    
          


