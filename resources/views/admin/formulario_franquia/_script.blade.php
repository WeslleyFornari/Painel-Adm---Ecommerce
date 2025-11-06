<script type="text/javascript">

$(document).ready(function() {

// DELETAR
        $("body").on('click', '.deletar-form', function (event) {

        event.preventDefault(); // Prevents the default behavior of the link
        var url = $(this).attr('href'); // Gets the route{id} EDIT -> DELETE
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

                        window.location.href = "{{route('admin.form-nao-encontrado.lista')}}"  
                    },
                });
            }
        });
        });
    
    
   

});
</script>