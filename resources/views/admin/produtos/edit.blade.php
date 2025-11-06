@extends('layouts.app')
@section('assets')
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css" rel="stylesheet">
@endsection
@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
   
        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.produtos.update', $produto->id)}}"  id="formStore" method="POST" enctype="multipart/form-data">
                @csrf
                <ul class="nav nav-tabs" id="myTab" role="tablist">

                    <li class="nav-item" role="presentation">
                        <button class="nav-link active btn-prod" id="produto-tab" data-bs-toggle="tab" data-bs-target="#produto" type="button" role="tab" aria-controls="produto" aria-selected="true">Produto</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link btn-carac" id="caracteristicas-tab" data-bs-toggle="tab" data-bs-target="#caracteristicas" type="button" role="tab" aria-controls="caracteristicas" aria-selected="false">Caracteristicas</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link btn-media" id="media-tab" data-bs-toggle="tab" data-bs-target="#media" type="button" role="tab" aria-controls="media" aria-selected="false">Media</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link btn-promo" id="promocoes-tab" data-bs-toggle="tab" data-bs-target="#promocoes" type="button" role="tab" aria-controls="promocoes" aria-selected="false">Promoções</button>
                    </li>

                    <!-- <li class="nav-item" role="presentation">
                        <button class="nav-link btn-prod_relac" id="prod_relac-tab" data-bs-toggle="tab" data-bs-target="#prod_relac" type="button" role="tab" aria-controls="prod_relac" aria-selected="false">Produtos Relacionados</button>
                    </li> -->

                    </ul>
                    <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade prod show active" id="produto" role="tabpanel" aria-labelledby="produto-tab"> @include('admin.produtos._produtos')</div>
                    <div class="tab-pane fade carac" id="caracteristicas" role="tabpanel" aria-labelledby="caracteristicas-tab">@include('admin.produtos._caracteristicas')</div>
                    <div class="tab-pane fade media" id="media" role="tabpanel" aria-labelledby="media-tab">@include('admin.produtos._media')</div>
                    <div class="tab-pane fade promo" id="promocoes" role="tabpanel" aria-labelledby="promocoes-tab">@include('admin.produtos._promocoes')</div>
                    <!-- <div class="tab-pane fade prod_relac" id="prod_relac" role="tabpanel" aria-labelledby="prod_relac-tab">@include('admin.produtos._produtos-relacionados')</div> -->
                    </div>
                    <div class="row">
                        @if ($catalogo)
                            <div class="col">
                                <a class="btn btn-secondary m-0" href="{{route('admin.produtos.catalogo.index')}}">Voltar</a>
                            </div>
                        @else
                            <div class="col">
                                <a class="btn btn-secondary m-0" href="{{route('admin.produtos.index')}}">Voltar</a>
                            </div>
                        @endif
                        <div class="col text-end">
                            <button type="submit" class="btn btn-success">Atualizar</button>
                        </div>
                    </div>
                </form>
           </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>
@include('admin.produtos._script-produto')
<script>
   $(".select2-tags").select2({
  tags: true,
  theme: "bootstrap-5",
});
$("body").on('submit',"#formStore",function (e) {
    e.preventDefault();

    $("span.error").remove();
    $.ajax({
        type: "POST",
        url: $(this).attr('action'),
        data: $(this).serialize(),
        success: function (data) {
            swal({
                title: "Parabéns",
                text: "Atualizar realizado com sucesso!.",
                icon: "success",
            }).then(function() {
                //window.location.href = '{{route("admin.produtos.index")}}';
            });
            $(".disabled").remove();
        },
        error: function (xhr, status, err) {
            var response = JSON.parse(xhr.responseText);
            console.log(response);
            if (response && response.status == 422) {
                if(response.campo == 'foto'){
                    foto();
                }
                else{
                    produto();
                }
                var el = $(document).find('[name="' + response.campo + '"]');
                if (el.length > 0) {
                    el.after($('<span class="error" style="color: red;">' + response.msg + '</span>'));
                } else {
                    console.log('Campo não encontrado: ' + response.campo);
                }
            }
        }
    });
});


</script>
@endsection
  