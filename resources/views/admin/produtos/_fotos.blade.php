<h4>Fotos</h4>  
<input type="hidden" name="foto">
<label for="exampleInputFile" class="control-label">Imagens<small>(500 x 500px)</small></label>
<x-upload-file target="logo" collum="id_media" type="gallery" :media="$produto?->foto" />