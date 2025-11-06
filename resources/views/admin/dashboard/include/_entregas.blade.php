<form id="filtrar-entregas" method="POST" action="{{ route('admin.filtrar_entregas', ['tipo' => 'entregas'])  }}">
@csrf  
<div class="row my-4">

    <div class="col-2">
        <h4>Entregas</h4>
    </div>
        
    <div class="col-3">
        <label for="">Nome do cliente</label>
    <input type="text" class="form-control" size="5" id="procurar" name="procurar" placeholder="Procurar...">
    </div>

    <div class="col-2">
    <label for="">Data de inicio</label>
        <input type="text" name="data_inicial" value="" id="placeholder-text" class="text-center form-input flatPicker"
        placeholder="00/00/00">
    </div>

    <div class="col-1 text-center" style="padding-top:30px">
        até
    </div>

    <div class="col-2">
    <label for="">Data de fim</label>
        <input type="text" name="data_final" id="placeholder-text" value="" class="text-center form-input flatPicker"
        placeholder="00/00/00">
    </div>
    <div class="col-2 " style="padding-top:30px">
        <a href="#" class="limpar-filtro"><span class="ms-1">Limpar</span></a>
    </div>

</div>
</form>

    <div class="lista-Entregas">
        
    </div>


