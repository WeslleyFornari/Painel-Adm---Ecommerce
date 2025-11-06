<script>
tipo();
modalidade();
CheckCatalogo();
 

$("body").on('click', '.adicionar', function (e) {
    e.preventDefault();
    var caracteristicas = document.querySelector('#caracteristicasview');
    var newDiv = document.createElement("div");
    newDiv.classList.add("col-12", "mt-2", "row");
    newDiv.innerHTML = `
    <div class="col-4">
        <span class="titulo"> Título: </span>
        <input type="text" name="caracteristicas[titulo][]" value="" class="form-control" >
    </div>
    <div class="col-4">
        <span class="titulo"> Descrição: </span>
        <input type="text" name="caracteristicas[descricao][]" value="" class="form-control" >
    </div>
    <div class="col-4">
        <span class="titulo"></span><br>
        <a href="#" class="btn btn-icon-only btn-danger close" onclick="excluirCaracteristica(this)"><i class="fas fa-trash"></i></a>
    </div>
    `;
    caracteristicas.appendChild(newDiv);
});

function excluirCaracteristica(elemento) {
    var div = elemento.parentNode.parentNode;
    div.parentNode.removeChild(div);
}

var contadorVideos = 0;

$("body").on('click', '.adicionarVideos', function (e) {
    e.preventDefault();
    var videos = document.querySelector('#videos');
    var newDiv = document.createElement("div");
    newDiv.classList.add("col-12", "mt-2", "row");
    contadorVideos++;
    newDiv.innerHTML = `
    <div class="col-4">
        <span class="titulo"> Id do Vídeo: </span>
        <input type="text" name="videos[url][]" value="" class="form-control" >
    </div>
    <div class="col-4">
        <span class="titulo"> Ordem: </span>
        <input type="text" name="videos[ordem][]" value="${contadorVideos}" class="form-control" >
    </div>
    <div class="col-4">
        <span class="titulo"></span><br>
        <a href="#" class="btn btn-icon-only btn-danger close" onclick="excluirVideos(this)"><i class="fas fa-trash"></i></a>
    </div>
    `;
    videos.appendChild(newDiv);
});

function excluirVideos(element) {
    element.parentNode.parentNode.remove();
    contadorVideos--;
}

var cont = 0;

$("body").on('click', '.adicionarPromocoes', function (e) {
    e.preventDefault();
    var promocoes = document.querySelector('#promocoesview');
    var newDiv = document.createElement("div");
    newDiv.classList.add("col-12", "mt-2", "row");
    newDiv.innerHTML = `
        <div class="col-2">
            <span class="titulo"> De (dias): </span>
            <input type="text" name="promocoes[de][]" value="" class="form-control" placeholder="">
        </div>
        <div class="col-2">
            <span class="titulo"> Até (dias): </span>
            <input type="text" name="promocoes[ate][]" value="" class="form-control">
        </div>
        <div class="col-2">
            <span class="titulo"> Tipo: </span>
            <select class="form-select" name="promocoes[tipo][]" id="tipo_desconto_${cont}" data-cont="${cont}">
                <option value="">Selecione</option>
                <option value="porcentagem">Porcentagem (%)</option>
                <option value="real">Real (R$)</option>
            </select>
        </div>
        <div class="col-3" id="desconto_${cont}">
            <span class="titulo"> Desconto: </span>
            <input type="text" name="promocoes[desconto][]" value="" class="form-control" disabled>
        </div>
        <div class="col-2">
            <span class="titulo"></span><br>
            <a href="#" class="btn btn-icon-only btn-danger close" onclick="excluirPromocoes(this)"><i class="fas fa-trash"></i></a>
        </div>
    `;
    promocoes.appendChild(newDiv);
    cont++;
});

function excluirPromocoes(element) {
    element.parentNode.parentNode.remove();
}

$("body").on('change', '[id^=tipo_desconto_]', function (e) {
    e.preventDefault();
    var tipo = $(this).val();
    var cont = $(this).data('cont'); 

    var desconto = document.querySelector('#desconto_' + cont);
    desconto.innerHTML = `
        <span class="titulo"> Desconto: (${tipo === 'real' ? 'R$' : '%'})</span>
        <input type="text" name="promocoes[desconto][]" value="" class="form-control ${tipo === 'real' ? 'moneyMask' : ''}">
    `;

    if (tipo === 'real') {
        $(`#desconto_${cont} .moneyMask`).mask('000.000.000.000.000,00', { reverse: true });
    }
});
$("body").on('change', 'select[name="id_franqueado"]', function (e) {
    e.preventDefault();
    var id = $(this).val();
    var url = '{{ route("admin.produtos.searchFranquia", ["id" => ":id"]) }}';
    url = url.replace(':id', id);

    $.get(url)
        .done(function (data) {
           $('#valores_aluguel').html(data);
        })

   
});

function tipo(){
    var tipo = $("#tipo").val();

    var role = '{{Auth::user()->role}}';

    if(tipo != 'toy'){
        $("#modalidadeInput").val('alugar');
        $("#modalidade").css('display', 'none');
        $(".cat_trip").css('display', 'block');
        $(".cat_toy").css('display', 'none');
        $("#produto_recomendo").css('display', 'block');
        $("#promocoes-tab").css('display', 'block');

        if(role != 'franqueado'){
            $("#card_franquia").css('display', 'none');
        }
    }
    else{
        $("#modalidade").css('display', 'block');
        $(".cat_trip").css('display', 'none');
        $("#promocoes-tab").css('display', 'none');
        $(".cat_toy").css('display', 'block');
        $("#produto_recomendo").css('display', 'none');

        if(role != 'franqueado'){
            $("#card_franquia").css('display', 'block');
        }

        $("#modalidadeInput").val($("select[name='modalidade']").val());

    }

    modalidade();

    
}
$("body").on('change', "select[name='tipo']", function (e) {
    e.preventDefault();
    tipo();
    modalidade();
    CheckCatalogo();
});

function modalidade(){
    var modalidade = $("#modalidadeInput").val();

    console.log(modalidade);
    var tipo = $("#tipo").val();

    if(modalidade == 'alugar' && tipo == 'toy'){
        $("#valor_div").css('display', 'none');
        $("#valores_aluguel").css('display', 'block');
        var valorText = document.querySelector('#valor');
        valorText.innerHTML = `Valor diário: *`;
    }
    else if(modalidade == 'alugar' && tipo == 'trip'){
        $("#valor_div").css('display', 'block');
        $("#valores_aluguel").css('display', 'none');
        var valorText = document.querySelector('#valor');
        valorText.innerHTML = `Valor diário: *`;
    }
    else if(modalidade == 'vender'){
        $("#valores_aluguel").css('display', 'none');
        $("#valor_div").css('display', 'block');
        var valorText = document.querySelector('#valor');
        valorText.innerHTML = `Valor Venda: *`;
    }
    else if(modalidade == 'alugar_vender'){
        $("#valores_aluguel").css('display', 'block');
        $("#valor_div").css('display', 'block');
        var valorText = document.querySelector('#valor');
        valorText.innerHTML = `Valor Venda: *`;
    }
}
$("body").on('change', "select[name='modalidade']", function (e) {
    e.preventDefault();
    $("#modalidadeInput").val($(this).val());
    modalidade();
    tipo();
});
function produto(){
    var carac = document.querySelector('.carac');
    var prod = document.querySelector('.prod');
    var media = document.querySelector('.media');
    var promo = document.querySelector('.promo');
    var btn_carac = document.querySelector('.btn-carac');
    var btn_prod = document.querySelector('.btn-prod');
    var btn_media = document.querySelector('.btn-media');
    var btn_promo = document.querySelector('.btn-promo');
    prod.classList.add('active', 'show');
    carac.classList.remove('active', 'show');
    media.classList.remove('active', 'show');
    promo.classList.remove('active', 'show');
    btn_prod.classList.add('active');
    btn_carac.classList.remove('active');
    btn_media.classList.remove('active');
    btn_promo.classList.remove('active');
}
function caracteristicas(){
    var carac = document.querySelector('.carac');
    var prod = document.querySelector('.prod');
    var media = document.querySelector('.media');
    var promo = document.querySelector('.promo');
    var btn_carac = document.querySelector('.btn-carac');
    var btn_prod = document.querySelector('.btn-prod');
    var btn_media = document.querySelector('.btn-media');
    var btn_promo = document.querySelector('.btn-promo');
    carac.classList.add('active', 'show');
    prod.classList.remove('active', 'show');
    media.classList.remove('active', 'show');
    promo.classList.remove('active', 'show');
    btn_carac.classList.add('active');
    btn_prod.classList.remove('active');
    btn_media.classList.remove('active');
    btn_promo.classList.remove('active');
}
function foto(){
    var carac = document.querySelector('.carac');
    var prod = document.querySelector('.prod');
    var media = document.querySelector('.media');
    var promo = document.querySelector('.promo');
    var btn_carac = document.querySelector('.btn-carac');
    var btn_prod = document.querySelector('.btn-prod');
    var btn_media = document.querySelector('.btn-media');
    var btn_promo = document.querySelector('.btn-promo');
    media.classList.add('active', 'show');
    prod.classList.remove('active', 'show');
    carac.classList.remove('active', 'show');
    promo.classList.remove('active', 'show');
    btn_media.classList.add('active');
    btn_prod.classList.remove('active');
    btn_carac.classList.remove('active');
    btn_promo.classList.remove('active');
}
function promocoes(){
    var carac = document.querySelector('.carac');
    var prod = document.querySelector('.prod');
    var media = document.querySelector('.media');
    var promo = document.querySelector('.promo');
    var btn_carac = document.querySelector('.btn-carac');
    var btn_prod = document.querySelector('.btn-prod');
    var btn_media = document.querySelector('.btn-media');
    var btn_promo = document.querySelector('.btn-promo');
    promo.classList.add('active', 'show');
    prod.classList.remove('active', 'show');
    carac.classList.remove('active', 'show');
    media.classList.remove('active', 'show');
    btn_promo.classList.add('active');
    btn_prod.classList.remove('active');
    btn_carac.classList.remove('active');
    btn_media.classList.remove('active');
}

$("body").on('click', '.caracteristica', function (e) {
    e.preventDefault();
    caracteristicas();
});
$("body").on('click', '.media-prox', function (e) {
    e.preventDefault();
    foto();
});
$("body").on('click', '.promo-prox', function (e) {
    e.preventDefault();
    promocoes()
    
});

$(document).ready(function() {
    $('#summernote').summernote({
        tabsize: 2,
        height: 200
    });
    $('#summernote_orientacoes').summernote({
        tabsize: 2,
        height: 200
    });
});

$("body").on('submit', '.formMarca', function(e) {

e.preventDefault();

$.ajax({

    url: $(this).attr('action'),
    type: "POST",
    data: $(this).serialize(),

    success: function(response) {

        $(".formMarca")[0].reset();
        $("#modalMarca").modal('hide');

        $('.modal-backdrop').hide();

        let novaMarca = `<option value="${response.marcas.id}">${response.marcas.nome}</option>`;
        $("#marcas").append(novaMarca).val(response.marcas.id);
    },

    error: function(err) {

    }
});
});

$("body").on('click', '#abrirMarca', function (e) {
    $("#modalMarca").modal('show');
    $('.modal-backdrop').show();
});

function CheckCatalogo(){
    var catalogo = $('#CheckCatalogo').is(':checked');

    if (catalogo){
        $("#valor_div").css('display', 'none');
        $("#valores_aluguel").css('display', 'none');
        $("#card_franquia").css('display', 'none');
    }
    else{
        tipo();
    }
}
$("body").on('change', '#CheckCatalogo', function (e) {
    e.preventDefault();
    var isChecked = $(this).is(':checked'); 
    var nome = $('input[name="nome"]').val().trim();

    console.log(nome);

    if (nome) {
        if (isChecked) {
            var url = '{{ route("admin.produtos.catalogo.buscar", ["nome" => ":nome"]) }}';
            url = url.replace(':nome', encodeURIComponent(nome));

            $.get(url)
                .done(function (data) {
                    console.log('Produto catalogado com sucesso.');
                })
                .fail(function () {
                    $('#CheckCatalogo').prop('checked', false);
                    swal({
                        title: "Erro",
                        text: "Produto já existe no catálogo",
                        icon: "error",
                    });
                });
        }
    } else {
        $('#CheckCatalogo').prop('checked', false);
        swal({
            title: "Erro",
            text: "É necessário preencher o nome antes de selecionar o produto para o catálogo.",
            icon: "error",
        });
    }

    CheckCatalogo();
});


</script>
