@extends('layouts.app')



@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">


                <div class="collapse" id="collapse-Config">
                    <div class="card shadow mb-4">
                        <div class="card-body " id="cadastro-config">
                  <!--      <a href="#" class="tooglegeCollapse float-right" data-target="#collapse-User"><i class="fas fa-times"></i></a> -->
                            @include('admin.configuracoes.cadastrar')
                        </div>
                    </div>
                </div>

                <div class="collapse" id="collapse-Config2">
                    <div class="card shadow mb-4">
                        <div class="card-body " id="edit-config">
                    <!--      <a href="#" class="tooglegeCollapse float-right" data-target="#collapse-User"><i class="fas fa-times"></i></a> -->
                          @include('admin.configuracoes.cadastrar')
                        </div>
                    </div>
                </div>
   
        <div class="card">
            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-7 ps-4 my-2">
                        <h4>Configurações</h4>          
                    </div>
                    @if(permissao('configuracoes')->criar == 'sim')
                    <div class="col-5 my-2 pe-4 text-end">
                    <a class="btn btn-primary tooglegeCollapse" type="button" 
                       data-target="#collapse-Config">Cadastrar</a>
                    </div>
                    @endif
                </div>
                <div id="lista-Config">
                    @include('admin.configuracoes._list')
                </div> 
                <div class="row mt-2 justify-content-center">
                        <div class="col-sm-12 mx-auto">
                            {{ $configuracoes->links() }}
                       </div>
                </div>
               
           </div>
        </div>
        

    </div>
</div>

@endsection

@section('scripts')

<script>


// CADASTRAR
    $("body").on('submit','#cadastrar-configuracoes', function(e) {

    e.preventDefault();
    var formData = $(this).serialize();
    console.log(formData);

    $("span.error").remove()

    $.ajax({
        url: '{{route("admin.configuracoes.store")}}',
        type: "POST",
        data: formData,
        
        success: function(response) {
        console.log(response);

        swal({
            title: "Parabéns",
            text: "Cadastro realizado com sucesso!.",
            icon: "success",
        })

        $("#cadastrar-configuracoes")[0].reset();
         
            $('#lista-Config').html(response);

        },

        error: function(err) {
            console.log(err);

            if (err.status == 422) { 

                console.log(err.responseJSON);

                $('#success_message').fadeIn().html(err.responseJSON.message);
                
                console.warn(err.responseJSON.errors);
                
                $.each(err.responseJSON.errors, function (i, error) {
                    var el = $(document).find('[name="' + i + '"]');
                    el.after($('<span class="error" style="color: red; font-size:12px; font-weight: bold; margin-left:10px; border: none;">' + error[0] +
                        '</span>'));
                });
            }
        },      
    });
    });

// EDITAR
    $("body").on('click','.editar-configuracao',function() {
       
        event.preventDefault(); // Impede o comportamento padrão do link
        var url = $(this).attr('href'); // Pega a route{id} EDIT -> DELETE
        console.log(url);

        $.ajax({
            url: url,
            type: "GET",

            success: function(response) {
                 //swal({
                    //title: "Parabéns",
                    //text: "Editado com sucesso!.",
                    //icon: "success",
                //})
                
                $("#edit-config").html(response);
                $("#collapse-Config2").collapse('show');
            },       
        });
    });

// ATUALIZAR
$("body").on('submit','#atualizar-configuracoes', function(e) {

    e.preventDefault();
    var formData = $(this).serialize();
    console.log(formData);
   
    $("span.error").remove()

    $.ajax({

        url: $(this).attr('action'),  
        type: "POST",                
        data: formData,
        
        success: function(response) {

            swal({
                    title: "Parabéns",
                    text: "Editado com sucesso!.",
                    icon: "success",
                })
            
            console.log(response);

            $("#atualizar-configuracoes")[0].reset();
            $("#collapse-Config2").collapse('hide');
            $('#lista-Config').html(response);
            
        }, 

        error: function(err) {
            console.log(err);

            if (err.status == 422) { 

                console.log(err.responseJSON);

                $('#success_message').fadeIn().html(err.responseJSON.message);
                
                console.warn(err.responseJSON.errors);
                
                $.each(err.responseJSON.errors, function (i, error) {
                    var el = $(document).find('[name="' + i + '"]');
                    el.after($('<span class="error" style="color: red; font-size:12px; font-weight: bold; margin-left:10px; border: none;">' + error[0] +
                        '</span>'));
                });
            }
        },      
    });
    });


// DELETAR
    $("body").on('click', '.deletar-configuracao', function (event) {
   
        event.preventDefault(); 
        var url = $(this).attr('href'); 
        console.log(url);

        swal({
            title: `Você tem certeza que deseja apagar as informações?`,
            text: "",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                
                $.ajax({
                    url: url,
                    type: "GET",
                    success: function (response) {

                        $("#lista-Config").html(response);

                    
                    },
                });
            }
        });
    });

</script>
@endsection







  