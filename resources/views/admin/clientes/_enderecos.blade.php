@if($cliente)
@foreach($cliente->enderecos as $endereco)
    @if($endereco->numero)
    <div class="row">
        <div class="col-6">
            <div class="d-flex m-3 ms-0">
                <div class="me-2" style="width: 40px; height: 40px; border-radius: 50%; background: #fff; display: flex; align-items: center; justify-content: center;">
                    <i class="fad fa-map-marked-alt"></i>
                </div>
                <label class="form-check-label" for="endereco">
                    {{$endereco->apelido}}<br>
                    {{$endereco->enderecoCompleto()}} <br>
                    {{$endereco->enderecoCompleto2()}}
                </label>
            </div>
        </div>
        <div class="align-content-center col-2 row">
            <a href="{{route('admin.clientes.deleteEndereco', $endereco->id) }}" class="btn btn-sm btn-icon-only btn-danger btn-destroyEndereco"> <i class="fas fa-trash"></i> </a>          
        </div>
    </div>
    @endif
@endforeach
<div class="" id="enderecosview"></div>
<div class="col-12 d-flex pb-3">
    <a href="#" class="abrirEndereco" data-usuario="{{$cliente->id}}">
        <i class="fas fa-plus-circle" style="font-size:35px;"></i> 
        <span style="font-size: 20px" class="ps-2">Adicionar Endereço</span>
    </a>
</div>
@endif