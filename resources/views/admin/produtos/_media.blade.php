<div class="col-12 my-2">
    <h4>Videos</h4>  
    @if($produto)
    @foreach ($produto->video as $video)
    <div class="col-12 mt-2 row vds">
        <div class="col-4">
            <span class="titulo"> Id do Vídeo:</span>
            <input type="text" name="videos[url][]" value="{{$video->url}}" class="form-control" >
        </div>
        <div class="col-4">
            <span class="titulo"> Ordem:</span>
            <input type="text" name="videos[ordem][]" value="{{$video->ordem}}" class="form-control" >
        </div>
        <div class="col-4">
        <span class="titulo"></span><br>
        <a href="#" class="btn btn-icon-only btn-danger close" onclick="excluirVideos(this)"><i class="fas fa-trash" aria-hidden="true"></i></a>
        </div>
    </div>
    @endforeach   
    @endif
    <div class="" id="videos"></div>
    <a href="" class="btn btn-sm btn-primary adicionarVideos" style="font-size: 15px"><i class="fas fa-plus-circle" style="font-size: 15px"></i> Adicionar Videos</a>     
</div>

<div class="col-12 my-2" id="viewfoto">
    @include('admin.produtos._fotos')
</div>
<div class="text-end">
    <button type="button" class="btn btn-primary promo-prox">Próximo</button>
</div>