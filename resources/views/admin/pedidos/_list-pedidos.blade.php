
<div class="col-12">

        <div class="row bg-dark arial16-font text-light text-bold m-0 py-2">
            <div class="col-2">Número</div>
            <div class="col-2">Cliente</div>
            <div class="col-2">Valor</div>
            <div class="col-2">Frete</div>
            <div class="col-2 text-center">Status</div>
            <div class="col-2 text-center">Ações</div>
        </div>
</div>

<div class="col-12 mt-4">
    @foreach($pedidos as $k => $ped)
        <div class="row m-0 py-2 border-bottom arial14-font-normal">
            <div class="col-2">
                {{$ped->numero ?? ''}}
            </div>
            <div class="col-2">
                {{$ped->cliente->name ?? ''}}
            </div>
            <div class="col-2">
                R$ {{getMoney($ped->valor_total) ?? ''}}
            </div>
            <div class="col-2">
                {{$ped->tipo_frete}}
            </div>
            <div class="col-2 justify-content-center d-flex">
                <select class="form-select btn-primary status" name="id_status" style="background-color: #00194A; font-size: 15px; font-weight: 600" data-id="{{$ped->id}}">
                    @foreach ($base as $status)
                        <option @if($ped->id_status == $status->id) selected @endif value="{{$status->id}}">{{$status->nome}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-2 text-center">
                <a href="{{route('admin.pedidos.preview', $ped->id)}}" target="_blank" class="btn btn-icon-only btn-secondary"><i class="fas fa-eye"></i></a>
                @if(permissao('clientes')->deletar == 'sim')
                    <a href="{{route('admin.pedidos.delete', $ped->id) }}" class="btn btn-icon-only btn-danger btn-exclude"> <i class="fas fa-trash"></i> </a>          
                @endif
            </div>
        </div>
    @endforeach
   
</div>
      
<div class="p-4 d-flex justify-content-center">
  {!! $pedidos->links() !!}
</div>      
    
          


