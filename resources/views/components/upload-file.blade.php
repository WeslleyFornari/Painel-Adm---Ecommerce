<div>   
    <div class="wrapper-upload" >
			<div>
				<button type="button" class="btn btn-primary openPopUpMedia" data-type="{{$type}}" data-target="{{$target}}" data-collum="{{$collum}}">
					<i class="fa fa-cloud-upload" aria-hidden="true"></i> Selecionar Imagem
				</button>
			</div>
			<div class="preview ps-4" >
			@isset($media)
				@php
					$count = 0;
				@endphp
					@if($type == 'single')
						@if(@$media && @$media->midiaDinamica($collum))
						
						<input type="hidden" name="{{$collum}}" value="{{ @$media->midiaDinamica($collum)->id }}" />
						<a href="#" class="remove" data-file="{{ @$media->midiaDinamica($collum)->id }}">
							<i class="fas fa-times"></i>
						</a>
						<img src="{{ @$media->midiaDinamica($collum)->fullpatch() }}" alt=""><Br>

						@else
							<input type="hidden" name="{{$collum}}" value="" />
						@endif
					@endif

					@if($type == 'gallery')
					<ul class="list-gallery ordenar" id="sortable-list">
						@foreach ($media as $med)
							@if(@$med && @$med->midiaDinamica($collum))

							

								<li class="position-relative">
									<i class="fa fa-arrows-h" aria-hidden="true"></i> 
									<input type="hidden" name="{{$collum}}[]" value="{{ @$med->midiaDinamica($collum)->id }}" />
									<input type="hidden" class="ordemGaleria" name="ordem[]" value="{{ $count }}">
									<img src="{{ @$med->midiaDinamica($collum)->fullpatch() }}" alt=""> 
									<a href="#" class="remove" data-file="{{ @$med->midiaDinamica($collum)->id }}">
										<i class="fas fa-times" aria-hidden="true"></i>
									</a>
									
								</li>
								@php
									$count++;
								@endphp
							
							@else
								<input type="hidden" name="{{$collum}}" value="" />
							@endif
							
						@endforeach
					</ul>
					@endif
				@endisset
			</div>
    </div>
</div>

    @push('assets')
    <style>
        .preview {
            position: relative;
            display: inline-block;
        }
                .preview img {
            height: 100px;
            border: 1px solid;
            /* padding: 2px; */
            border-radius: 5px;
            box-shadow: 0 0 8px #ededed;
            width: 100px;
            object-fit: contain;
        }
        .preview .remove {
            position: absolute;
            right: 0;
            top: 0;
            background: red;
            border-radius: 0px 5px 0px 5px;
            padding: 2px 6px;
            color: #fff;
        }
        .preview .list-gallery {
   
 

}
        .preview .list-gallery li{
            list-style: none;
         
            float: left;
        }
    </style>
    @endpush
    @push('scripts')

    <div class="modal fade" id="modalMedia" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-fullscreen" role="document">
            <div class="modal-content" style="padding: 10px;" >
                <div class="modal-body">
            <div id="contentMedia"></div>
            </div>
            <div class="clearfix"></div>
        </div>
        </div>
        </div>

        
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var sortable = new Sortable(document.getElementById('sortable-list'), {
            animation: 150,
            onEnd: function (evt) {
                updateOrder();
            }
        });
        
        function updateOrder() {
            var listItems = document.querySelectorAll('#sortable-list li');
            listItems.forEach((item, index) => {
                item.querySelector('.ordemGaleria').value = index;
            });
        }
    });
    </script>
<script>
    
    
      $("body").on('click',".openPopUpMedia",function(e){
      e.preventDefault();
        $(this).closest('.wrapper-upload').addClass('target-active');
      var target = $(this).data('target');
      var collum = $(this).data('collum');
      var type = $(this).data('type');
     
      openPopUpMedia(target,collum,type)
    })
      $("body").on('click',".remove",function(e){
      e.preventDefault();
       // $(this).closest('.preview').find('img').remove();
        // var $input = $(this).closest('.preview').find('input[type="hidden"]');
        // $(this).closest('.preview').html($input.val(''))
		$(this).closest('li').remove()
    })

    function openPopUpMedia(target,collum,type){
        if(target){
            localStorage.setItem("media_target", target);
        }
        if(collum){
            localStorage.setItem("media_collum", collum);
        }
        if(type){
            localStorage.setItem("media_type", type);
        }
        if($("#contentMedia").html() == ""){
        $.get('{{route("admin.media.popUp")}}',function(data){
            $("#contentMedia").html(data);
            $('#modalMedia').modal('show');
            $("#contentMedia").find('.checkFile').prop('checked',false);

        })
        }else{
        $('#modalMedia').modal('show')
        //$("#modalMedia .content").removeClass('hidden')
        $("#contentMedia").find('.checkFile').prop('checked',false);

        }
        }
        


</script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.4/clipboard.min.js"></script>
<script src="https://johnny.github.io/jquery-sortable/js/jquery-sortable.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
$("body").on('click', '.btnSendNewFolder', function(e) {
		e.preventDefault();
		var url = "{{route('admin.media.newFolder')}}"; // the script where you handle the form input.
		
        var data = new FormData();

			data.append('name_folder', $(this).closest('.formNewFolder').find("input[name='name_folder']").val());
			data.append('_token', '{{ csrf_token() }}');


		$.ajax({
			type: "POST",
			url: url,
			cache: false,
			contentType: false,
			processData: false,
			data: data,
			success: function(data) {
				$("#lista-pastas").html(data);
			},
            error: function (xhr, status, error) {
					
						var response = JSON.parse(xhr.responseText);
                $.toast({
                    text : response.error,
                    // custom toast title
                    heading: 'Atenção',
                    // show/hide transition effects.
                    // fade, slide or plain.
                    showHideTransition: 'fade',
                    // show a close icon
                    allowToastClose: true,
                    // auto hide after a timeout
                    hideAfter: 5000,
                    // loader
                    loader: true,
                    loaderBg: '#9EC600',
                    // stack length
                    stack: 5,
                    // bottom-left, bottom-right, bottom-center, 
                    // top-left,top-right
                    // top-center, mid-center
                    // or an object representing the left, right, top, bottom values
                    position: 'top-right',
                    // background color
                    bgColor: 'red',
                    // custom text color
                    textColor: '#fff',
                    // custom text align
                    textAlign: 'left',
                    // custom icon
                    icon: false,

// callback functions.
beforeShow: function () {},
afterShown: function () {},
beforeHide: function () {},
afterHidden: function () {},
onClick: function () {}
                    })
            }
		});
		e.preventDefault(); // avoid to execute the actual submit of the form.
	});
	function searchUser(id, input) {
		// Declare variables
		var input, filter, ul, li, a, i, txtValue;
		input = document.getElementById(input);
		filter = input.value.toUpperCase();
		ul = document.getElementById(id);
		li = ul.getElementsByTagName('li');
		// Loop through all list items, and hide those who don't match the search query
		for (i = 0; i < li.length; i++) {
			a = li[i].getElementsByClassName("nome")[0];
			txtValue = a.textContent || a.innerText;
			if (txtValue.toUpperCase().indexOf(filter) > -1) {
				li[i].style.display = "";
			} else {
				li[i].style.display = "none";
			}
		}
	}
	$("body").on('click', '.newFolter', function(e) {
		e.preventDefault();
		$(".box-newFolder").fadeIn('fast');
	})
	$("body").on('click', '.cancelNewFolder', function(e) {
		e.preventDefault();
		$(".box-newFolder").fadeOut('fast');
	})
	
	var ajaxLoading = false;
	function copyToClipboard(element) {
		$(element).select();
		document.execCommand("copy");
		$(".alert-success").html('Link Copiado').fadeIn('fast').delay(2000).fadeOut('fast', function() {
			$('#modalMedia').modal('hide');
		})
	}
	
		$('[data-toggle="tooltip"]').tooltip();
		function listFiles(folder, page = 1) {
			if (!folder) {
				folder = 'uploads';
			} else {}
			var data = new FormData();
			data.append('folder', folder);
			data.append('_token', '{{ csrf_token() }}');
			data.append('page', page);
			$(".list-files").html('Carregando...')
			$.ajax({
				url: '{{route("admin.media.list-files")}}',
				type: 'POST',
				cache: false,
				contentType: false,
				processData: false,
				data: data,
				success: function(result) {
					$(".list-files").html(result)
				}
			});
		}
		// listFiles();
		$(".list-files").on('click', 'a', function(e) {
			e.preventDefault();
		});
		$("body").on('click', '.actionRemove', function(e) {
			e.preventDefault();
			$.post('{{route("admin.media.delete-media")}}', $("#formFiles").serialize(), function(data) {
				//console.log(data);
				//var href = $('.pagination .active a').attr('href');
				//var page = $('.pagination .active a').attr('href').split("=");
				listFiles($("input[name='folder']").val());
			})
		})
		$("body").on('click', '.removeImg', function(e) {
			e.preventDefault();
			$.post('{{route("admin.media.delete-media")}}', $("#formFiles").serialize(), function(data) {
				//console.log(data);
				//var href = $('.pagination .active a').attr('href');
				//var page = $('.pagination .active a').attr('href').split("=");
				listFiles($("input[name='folder']").val());
			})
		})
		$("body").on('click', '.usarImage', function(e) {
			e.preventDefault();
			var count = $('input[name="file[]"]:checked').length;
			var val = $('.list-files input[name="file[]"]:checked').val();
			var preview = $('.target-active').find('.preview');
			var collum = localStorage.getItem("media_collum");
			var alvo = localStorage.getItem("media_target");
			var type = localStorage.getItem("media_type");
		
			if(type == "single"){
				$.get('{{route("admin.media.getFile")}}/' + val, function(data) {
					var html = '';
					html += '<input type="hidden" name="'+collum+'" value="' + data.id + '" />';
					html += '<a href="#" class="remove" data-file="' + data.arquivo + '">';
					html += '<i class="fas fa-times"></i> ';
					html += '</a>';
					html += '<img src="' + data.fullpatch + '">';
					
					$(preview).html(html);
					$('#modalMedia').modal('hide');
					$(".target-active").removeClass('target-active')
				});
			}
		
				if(type == "gallery"){
                   
                    if($('.target-active .list-gallery li').length == 0){ 
					var html = '<ul class="list-gallery ordenar" id="#sortable-list li"></ul>';
                        $(preview).html(html);
                    }
					$('.list-files input[name="file[]"]:checked').each(function (index) {
						
						$.get('{{route("admin.media.getFile")}}/'+$(this).val(),function(data){
							
							
							var itens = "";
						console.info(data)
							
							itens +='<li>'
							itens +='<i class="fa fa-arrows-h" aria-hidden="true"></i>';
							itens +=' <input type="hidden" name="'+collum+'[]" value="'+data.id+'" />';
							itens +='<input type="hidden" class="ordemGaleria" name="ordem[]" value="'+index+'">';
							itens +='<img src="' + data.fullpatch +'"  alt="">';
							itens +=' ';
							itens +='<a href="" class="btn btn-danger btn-xs  remove "  data-file="'+data.id+'">';
							itens +='<i class="fas fa-trash"></i>';
							itens +='</a>';
							itens +='';
							itens +='</li>';
							$(preview).find('.list-gallery').append(itens);
							
							$( ".ordenar" ).sortable({
								cancel:'.remover',
								distance: 0,
							
								update: function( event, ui ) {
								$(".ordenar li").each(function( index ) {
									$(this).find('.ordemGaleria').val(index)
								});
								}
							});
							$( ".ordenar" ).disableSelection();
						});
						
						
					});
				
				
					
				
					$('#modalMedia').modal('hide');
							$(".target-active").removeClass('target-active')
						//	$('#previewGaleria ul').append(html);
					}
			
			
			
			
			
		})
		$("body").on('click', '.checkFile', function(e) {
			if ($('.checkFile:checked').length > 0) {
				//$(".actionRemove").closest('.row').removeClass("hidden");
			} else {
				//$(".actionRemove").closest('.row').addClass("hidden");
			}
		})
		$("body").on('click', ".list-group a", function(e) {
			e.preventDefault();
			var folder = $(this).data('folder');
			$("#lista-pastas li").removeClass('active');
			$(this).closest('li').addClass('active')
			$("input[name='folder']").val(folder)
			listFiles(folder);
		})
		$(".pagination li").eq(1).html('<a class="page-link" href="{{route("admin.media.index")}}?page=1">1</a>');
		$("body").on('click','#formFiles .page-link',function(e) {
			e.preventDefault();
			var page = $(this).attr('href').split("=");
			$(".pagination").find(".active").removeClass("active");
			$(this).parent().addClass("active")
			listFiles($("input[name='folder']").val(), page[1]);
		})
		$('body').on('change', '#uploadArquivos', function() {
			$(this).siblings('.carregandoForm').fadeIn('fast');
			$("#carregandoForm").show(0);
			var data = new FormData();
			$.each($(this)[0].files, function(i, file) {
				data.append('file[]', file);
			});
			data.append('folder', $("input[name='folder']").val());
			data.append('folder_parent', $("input[name='folder_parent']").val());
			data.append('_token', '{{ csrf_token() }}');
			$('#listArquivos').css('opacity', 0.5);
			if (!ajaxLoading) {
				ajaxLoading = true;
				$.ajax({
					url: '{{route("admin.media.upload-media")}}',
					type: 'POST',
					cache: false,
					contentType: false,
					processData: false,
					data: data,
					success: function(result) {
						console.log(result);
						$('.carregandoForm:visible').fadeOut('fast');
						listFiles($("input[name='folder']").val());
						ajaxLoading = false;
						$('#listArquivos').css('opacity', 1);
					},
					error: function (xhr, status, error) {
						var errorContainer = $('#errorContainer');
						var response = JSON.parse(xhr.responseText);
						if (response.error) {
							errorContainer.html(response.error);
						} else {
							errorContainer.html('Ocorreu um erro desconhecido.');
						}

						$('.carregandoForm').css('display', 'none');
						ajaxLoading = false;
						$("input[name='folder']").val();
						$("input[name='folder_parent']").val();
					}

				});
			}
		});
	});
	$("body").on('click', "input[name='checkAll']",function(){
		$(".checkFile").click()
	});
</script>
@endpush