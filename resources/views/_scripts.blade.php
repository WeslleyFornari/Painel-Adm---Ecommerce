@if($modal)

<style>
    .mfp-bg{
        opacity: 0.2;
    }
</style>

<!-- Foto Magnific-->
<a id="trigger-popup" style="display:none;" class="foto-magnific" href="{{ asset(@$modal->foto_desktop)}}" >
    <img src="{{ asset(@$modal->foto_desktop)}}" class="img-fluid">
</a>

<!-- Youtube Magnific-->
<a class="popup-youtube" href="http://www.youtube.com/watch?v={{ $modal->link }}"></a>


@if(  ($modal->visibilidade == 'home' && Route::current()->getName() == 'home') || ($modal->visibilidade == 'todas') )
      
        @if ($modal->tipo == 'video')
       <script>

           $('.popup-youtube').magnificPopup({
               
                type: 'iframe',
                mainClass: 'mfp-fade',
                removalDelay: 160,
                preloader: false,
                fixedContentPos: false,

                iframe: {
                    patterns: {
                    youtube: {
                        index: 'youtube.com/',
                        id: 'v=',
                        src: 'https://www.youtube.com/embed/%id%?mute=1&autoplay=1'
                    }
                }
             }
            });

            $('.popup-youtube').magnificPopup('open');

        </script>

        @elseif ($modal->tipo == 'foto')

            <script>
            $('.foto-magnific').magnificPopup({
                    type: 'image',
                    closeOnContentClick: true,
                    mainClass: 'mfp-img-mobile',
                    image: {
                        verticalFit: true
                    }
                    
                });

                $('#trigger-popup').magnificPopup('open');
            </script>
        @endif
 @endif
@endif

<script>

// CADASTRAR
$("body").on('submit','.form-nao-encontrado', function(e) {


        e.preventDefault();
        var formData = $(this).serialize();
        console.log(formData);

        $(this).find(".loading").fadeIn('fast')
        $.ajax({
            url: '{{route("form-nao-encontrado.store")}}',
            type: "POST",
            data: formData,
            
            success: function(response) {
            console.log(response);
                $('.loading').fadeOut('fast');
                    swal({
                            title: "Enviado com sucesso",
                            text: "Em breve entraremos em contato!",
                            icon: "success",
                                }).then(function() {
                 
                                    
                                   
                                    $('.modal-form-nao-encontrado').find("form")[0].reset(); // Reseta o conteúdo do formulário dentro do modal
                                    $(".form-nao-encontrado")[0].reset(); // reseta o conteudo do form. 
                                });
                    },

            error: function(response) {
                     
            },      
        });
});





</script>