
   
        <div class="row bg-dark arial16-font text-light text-bold m-0 py-2">

                       
                        <div class="col-3">Pergunta</div>
                        <div class="col-4">Resposta</div> 
                        <div class="col-2 text-center">Franquia</div>
                        <div class="col-1">Status</div>

                        <div class="col-2 text-center">Ações</div>                 
        </div>

                <!--BODY-->
                @foreach($duvidas as $k => $duvida)
                <div class="row m-0 py-2 border-bottom arial14-font-normal align-duvidas-center">

                                       
                     <div class="col-2 text-center">{{$duvida->pergunta ?? ''}}</div>
                     <div class="col-5">{!! \Illuminate\Support\Str::limit(strip_tags($duvida->resposta), 108, '...') !!}</div>
                     <div class="col-2 text-center">{{$duvida->tipo_franqueado ?? ''}}</div>

                     <!-- TOGGLE SWITCH -->
                    @if($duvida->status == 'ativo')
                          <div class="col-1 justify-content-center">
                              <label class="switch">
                                <input type="checkbox" checked class="status-duvida" data-id="{{$duvida->id}}">
                                <span class="slider round"></span>
                              </label>
                             
                              <strong style="display:inline; ">Ativo</strong>
                        </div>
                     @else    
                           <div class="col-1 justify-content-center">
                              <label class="switch">
                                <input type="checkbox" class="status-duvida" data-id="{{$duvida->id}}">
                                <span class="slider round"></span>
                              </label>

                              <strong style="display:inline; ">Inativo</strong>
                            </div> 
                    @endif

                         <div class="col-2 text-center">
                            @if(permissao('duvidas')->visualizar == 'sim')
                               <a href="#"class="btn btn-info btn-sm show btn-icon-only preview-dudidas"><i class="fas fa-eye"></i></a>
                            @endif
                            @if(permissao('duvidas')->editar == 'sim')
                                <a href="{{route('admin.duvidas.edit', $duvida->id)}}" class="btn btn-sm btn-icon-only btn-secondary editar-duvidas"> <i class="fas fa-pencil"></i> </a>
                            @endif
                            @if(permissao('duvidas')->deletar == 'sim')
                                <a href="{{route('admin.duvidas.delete', $duvida->id) }}" class="btn btn-sm btn-icon-only btn-danger btn-destroy"> <i class="fas fa-trash"></i> </a>          
                            @endif
                        </div>   
                </div>
                @endforeach  
                <div class="row mt-5 justify-content-center">
                            <div class="col-sm-12 mx-auto align-center">
                            {!!$duvidas->links()!!}
                            </div>
                        </div>

                
                

    
            
    
          


