<script>
// function bloquear() {
//     var localizacao = $("input[name='localizacao']").val();

//     if (!localizacao) {
//         document.querySelectorAll('button, input, select, textarea, a').forEach(function(element) {
//             // Verifica se o elemento NÃO está dentro de #modalLocalizacao, #modalUnidades ou #pop-up
//             if (!element.closest('#modalLocalizacao') && 
//                 !element.closest('#modalUnidades') && 
//                 !element.closest('#pop-up') && 
//                 // Verifica se o elemento NÃO possui a classe 'btn-permitir' ou 'btn-nao-permitir'
//                 !element.classList.contains('btn-permitir') && 
//                 !element.classList.contains('btn-nao-permitir')) {
//                 element.classList.add('disabled');
//             }
//         });
//     }
// };

function bloquear() {
    var localizacao = $("input[name='localizacao']").val();

    if (!localizacao) {
        document.querySelectorAll('#categorias button, #categorias input, #categorias select, #categorias textarea, #categorias a').forEach(function(element) {
                element.classList.add('disabled');
        });
        document.querySelectorAll('#pesquisar input, #pesquisar select').forEach(function(element) {
                element.classList.add('disabled');
        });
    }
}

// document.addEventListener('click', function(event) {
//     var localizacao = $("input[name='localizacao']").val();
//     var modal = document.getElementById('modalLocalizacao');
//     var modal2 = document.getElementById('modalUnidades');
//     var pop_up = document.getElementById('pop-up');
//     var permitir = document.querySelector('.btn-permitir');
//     var isClickInsideModal = modal.contains(event.target);
//     var isClickInsideModal2 = modal2.contains(event.target);
//     var isClickInsidepop_up = pop_up.contains(event.target);
//     var isClickpermitir = permitir.contains(event.target);

//     if (!localizacao && !isClickInsideModal && !isClickInsideModal2 && !isClickInsidepop_up && !isClickpermitir) {
        
//         $("#modalLocalizacao").modal('show');
        
//     }
// });

document.addEventListener('click', function(event) {
    var localizacao = $("input[name='localizacao']").val();
    var modal = document.getElementById('modalLocalizacao');
    var modal2 = document.getElementById('modalUnidades');
    var pop_up = document.getElementById('pop-up');
    var permitir = document.querySelector('.btn-permitir');
    var areaBloqueado = document.getElementById('categorias');
    // var pesquisar = document.getElementById('pesquisar');

    var isClickInsideModal = modal.contains(event.target);
    var isClickInsideModal2 = modal2.contains(event.target);
    var isClickInsidepop_up = pop_up.contains(event.target);
    var isClickpermitir = permitir.contains(event.target);
    var isClickInsideAreaBloqueado = areaBloqueado.contains(event.target);
    // var isClickInsidepesquisar = pesquisar.contains(event.target);

    if (!localizacao && !isClickInsideModal && !isClickInsideModal2 && !isClickInsidepop_up && !isClickpermitir && isClickInsideAreaBloqueado) {
        $("#modalLocalizacao").modal('show');
    }
    // if (!localizacao && isClickInsidepesquisar) {
    //     $("#modalLocalizacao").modal('show');
    // }
});

</script>