<div class="col-12 my-2">
        <h4>Caracteristicas</h4>

        @if($produto)
        @foreach ($produto->caracteristica as $caracteristica)    
        <div class="col-12 mt-2 row">
            <div class="col-4">
                <span class="titulo"> Título:</span>
                <input type="text" name="caracteristicas[titulo][]" value="{{$caracteristica->titulo}}" class="form-control">
            </div>
            <div class="col-4">
                <span class="titulo"> Descriçao:</span>
                <input type="text" name="caracteristicas[descricao][]" value="{{$caracteristica->descricao}}" class="form-control">
            </div>
            <div class="col-4">
                <span class="titulo"></span><br>
                <a href="#" class="btn btn-icon-only btn-danger close" onclick="excluirCaracteristica(this)"><i class="fas fa-trash" aria-hidden="true"></i></a>
            </div>
        </div> 
        @endforeach
        @endif

    <div class="" id="caracteristicasview"></div>
    <a href="" class="btn btn-sm btn-primary adicionar" style="font-size: 15px"><i class="fas fa-plus-circle" style="font-size: 15px"></i> Adicionar Caracteristicas</a>     
</div>
<div class="text-end">
    <button type="button" class="btn btn-primary media-prox">Próximo</button>
</div>