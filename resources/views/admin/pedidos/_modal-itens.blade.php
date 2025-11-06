<form action="{{ route('admin.pedidos.EntregueDevolvido' , $pedido->id) }}" id="formStoreEntregueDevolvido" method="POST" enctype="multipart/form-data">
@csrf
<input type="hidden" name="tipo" value="{{$tipo}}">
<fieldset>
    @foreach ($pedido->itens as $itens)
    <div>
        <input type="checkbox" id="{{$itens->id}}" name="itens[]" value="{{$itens->id}}"/>
        <label for="{{$itens->id}}">{{$itens->produto->nome}}</label>
    </div>
    @endforeach
</fieldset>
<div class="row justify-content-end">
    <div class="col-6 text-end d-flex">
        <button type="submit" class="btn btn-primary m-2">Enviar</button>
        <button type="button" class="btn btn-secondary m-2" data-dismiss="modal">Close</button>
    </div>
</div>
</form>