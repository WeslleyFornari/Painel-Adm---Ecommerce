@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.produtos.store') }}" id="formStore" method="POST" enctype="multipart/form-data">
                    @csrf

                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active btn-prod" id="produto-tab" data-bs-toggle="tab" data-bs-target="#produto" type="button" role="tab" aria-controls="produto" aria-selected="true">Produto</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link btn-carac" id="caracteristicas-tab" data-bs-toggle="tab" data-bs-target="#caracteristicas" type="button" role="tab" aria-controls="caracteristicas" aria-selected="false">Características</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link btn-media" id="media-tab" data-bs-toggle="tab" data-bs-target="#media" type="button" role="tab" aria-controls="media" aria-selected="false">Mídia</button>
                        </li>
                        <li class="nav-item" role="presentation">
                        @if (Auth::user()->role != 'franqueado')
                            <button class="nav-link btn-promo" id="promocoes-tab" data-bs-toggle="tab" data-bs-target="#promocoes" type="button" role="tab" aria-controls="promocoes" aria-selected="false">Promoções</button>
                        @endif
                        </li>
                        @if (!$catalogo)
                        <li class="d-flex col justify-content-end">
                            <a type="button" id="abrirCatologo">Importar do Catálogo</a>
                        </li>
                        @endif
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
                            <button type="submit" class="btn btn-success">Salvar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalMarca" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel">Marca</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="conteudo-marcas">
                    @include('admin.produtos.marcas._cadastrar')
                </div>

            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="modalCatalogo" tabindex="-1" aria-labelledby="modalCatalogoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalCatalogoLabel">Importar Produto do Catálogo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('admin.produtos.ExportarProduto') }}" id="formStoreImportar" method="POST" enctype="multipart/form-data">
      @csrf
        <div class="modal-body">
            <select class="form-select" name="produto">
                <option value="">Selecione</option>
                @foreach($produtos_catalogo as $prod)
                    <option value="{{ $prod->id }}">{{ $prod->nome }}</option>
                @endforeach
            </select>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Importar</button>
        </div>
      </form>
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
    $("#formStore").submit(function(e) {
        e.preventDefault();
        $("span.error").remove();
        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success: function(data) {
                var catalogo = '{{ $catalogo }}';

                if (!catalogo) {
                    swal({
                        title: "Parabéns",
                        text: "Cadastro realizado com sucesso!",
                        icon: "success",
                    }).then(function() {
                        location.reload();
                    });
                } else {
                    swal({
                        title: "Parabéns",
                        text: "Cadastro realizado com sucesso!",
                        icon: "success",
                    }).then(function() {
                        window.location.href = '{{ route("admin.produtos.catalogo.index") }}';
                    });
                }
                
                $("#formStore")[0].reset();
                $(".disabled").remove();
            },
            error: function(xhr, status, err) {
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

$("#formStoreImportar").submit(function (e) {
    e.preventDefault();
    $("span.error").remove();
    $.ajax({
        type: "POST",
        url: $(this).attr('action'),
        data: $(this).serialize(),
        success: function (data) {
            $("#formStore select[name='id_franqueado']").val(data.produto.id_franqueado);
            $("#formStore input[name='nome']").val(data.produto.nome);
            $("#formStore select[name='tipo']").val(data.produto.tipo);
            $("#formStore select[name='modalidade']").val(data.produto.modalidade);

            tipo();
            modalidade();
            $("textarea[name='descricao']").summernote('code', data.produto.descricao);
            $("textarea[name='orientacoes']").summernote('code', data.produto.orientacoes);

            if (data.produto.tipo == 'trip'){
                $("#formStore select[name='categoria_trip']").val(data.produto.id_categoria);
            }
            else if(data.produto.tipo == 'toy'){
                $("#formStore select[name='categoria_toy']").val(data.produto.id_categoria);
            }
            $("#formStore select[name='marca']").val(data.produto.marca);
            $("#formStore input[name='peso_maximo']").val(data.produto.peso_maximo);
            $("#formStore").addClass('show');

            $("#formStore select[name='idade']").val(data.produto.idade);
           
            // if (data.valores_periodos) {
            //     data.valores_periodos.forEach((valorPeriodo) => {
            //         console.log(valorPeriodo);
            //         $('#valor_periodo-' + valorPeriodo.id_periodo).val(valorPeriodo.valor_periodo);
            //     });
            // }
            // $("#formStore input[name='valor_base_diaria']").val(data.produto.valor_base_diaria);
            var cont = 0;
            data.promocoes.forEach(function (promocao) {
            var promocoes = document.querySelector('#promocoesview');
            var newDiv = document.createElement("div");
            newDiv.classList.add("col-12", "mt-2", "row");

            newDiv.innerHTML = `
                <div class="col-2">
                    <span class="titulo">De:</span>
                    <input type="text" name="promocoes[de][]" value="${promocao.de || ''}" class="form-control">
                </div>
                <div class="col-2">
                    <span class="titulo">Até:</span>
                    <input type="text" name="promocoes[ate][]" value="${promocao.ate || ''}" class="form-control">
                </div>
                <div class="col-2">
                    <span class="titulo">Tipo:</span>
                    <select class="form-select" name="promocoes[tipo][]" id="tipo_desconto_${cont}" data-cont="${cont}">
                        <option value="">Selecione</option>
                        <option value="porcentagem" ${promocao.tipo === "porcentagem" ? "selected" : ""}>Porcentagem (%)</option>
                        <option value="real" ${promocao.tipo === "real" ? "selected" : ""}>Real (R$)</option>
                    </select>
                </div>
                <div class="col-3" id="desconto_${cont}">
                    <span class="titulo">Desconto:</span>
                    <input type="text" name="promocoes[desconto][]" value="${promocao.desconto || ''}" class="form-control" ${promocao.tipo ? "" : "disabled"}>
                </div>
                <div class="col-2">
                    <span class="titulo"></span><br>
                    <a href="#" class="btn btn-icon-only btn-danger close" onclick="excluirPromocoes(this)">
                        <i class="fas fa-trash"></i>
                    </a>
                </div>
            `;

            // Adiciona a nova div ao container de promoções
            promocoes.appendChild(newDiv);

            // Incrementa o contador
            cont++;
        });


            data.caracteristicas.forEach(function(caracteristica) {
                var caracteristicasContainer = document.querySelector('#caracteristicasview');
                
                // Cria o elemento principal
                var newDiv = document.createElement("div");
                newDiv.classList.add("col-12", "mt-2", "row");

                // Define o conteúdo interno da nova div
                newDiv.innerHTML = `
                    <div class="col-4">
                        <span class="titulo">Título:</span>
                        <input type="text" name="caracteristicas[titulo][]" value="${caracteristica.titulo || ''}" class="form-control">
                    </div>
                    <div class="col-4">
                        <span class="titulo">Descrição:</span>
                        <input type="text" name="caracteristicas[descricao][]" value="${caracteristica.descricao || ''}" class="form-control">
                    </div>
                    <div class="col-4">
                        <span class="titulo"></span><br>
                        <a href="#" class="btn btn-icon-only btn-danger close" onclick="excluirCaracteristica(this)">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>
                `;

                // Adiciona a nova div ao container
                caracteristicasContainer.appendChild(newDiv);
            });

            var url = '{{ route("admin.produtos.ExportarFotos", ["id" => ":id"]) }}';
            url = url.replace(':id', data.produto.id);

            $.get(url, function (data) {
                var viewfoto = $('#viewfoto');
                viewfoto.html(data); // Corrigido para usar innerHTML
            });

            $("#formStoreImportar")[0].reset();
            $(".disabled").remove();

            $('#modalCatalogo').modal('hide');
           $('.modal-backdrop').hide();
        },
        error: function (err) {
            console.log(err);
            if (err.status == 422) {
                console.log(err.responseJSON);
                $('#success_message').fadeIn().html(err.responseJSON.message);
                $.each(err.responseJSON.errors, function (i, error) {
                    var el = $(document).find('[name="' + i + '"]');
                    el.after($('<span class="error" style="color: red;">' + error[0] + '</span>'));
                });
            }
        }
    });
});

    // Store Marcas
    
$("body").on('click', '#abrirCatologo', function (e) {
    $('#modalCatalogo').modal('show');
    $('.modal-backdrop').show();
});
</script>
@endsection