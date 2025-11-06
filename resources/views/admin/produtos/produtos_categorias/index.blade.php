@extends('layouts.app')

@section ('assets')

@endsection
@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
   
        <div class="card">
            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-7 ps-4 my-2">
                        <h4>Produtos Categorias</h4>          
                    </div>
                    <div class="col-5 my-2 pe-4 text-end">
                            <a href="{{route('admin.produtos_categorias.new')}}" class="btn btn-primary">Adicionar</a>
                    </div>
                </div>
                <div id="lista-Produtos">
                    @include('admin.produtos.produtos_categorias._list-prod_cat')
                </div> 
                {{ $produtos_categorias->links() }}
           </div>
           <div class="card-footer">
                <div class="col">
                    <a class="btn btn-secondary m-0" href="{{route('admin.produtos.index')}}">Voltar</a>
                </div>
            </div>
        </div>
        

    </div>
</div>

@endsection

@section('scripts')
<script>


$("body").on('change', '.status-categoria', function (e) {
    e.preventDefault();
    var id = this.getAttribute('data-id');
    console.log("O estado do checkbox mudou! " + id);

    var url = '{{ route("admin.produtos_categorias.mudarStatus", ["id" => ":id"]) }}';
    url = url.replace(':id', id);
    $.get(url, function (data) {
        swal({
            title: "Parabéns",
            text: "Status alterado com sucesso!.",
            icon: "success",
        })
    });
});

    $(".btn-delete").click(function(e){
    var url = $(this).attr('href');
    e.preventDefault();

    swal({
    title: "Você tem certeza?",
    text: "Você removerá permanentemente este item",
    icon: "warning",
    dangerMode: true,
    buttons:{
        cancel: {
        text: "Cancel",
        value: null,
        visible: true,
        className: "",
        closeModal: true,
        },
        confirm: {
        text: "OK",
        value: true,
        visible: true,
        className: "",
        closeModal: true
        }
    }
    }) .then(willDelete => {
    if (willDelete) {

    $.ajax({
        url: url,
        type: 'GET',
        success: function(data){ 
            if (willDelete) {
               
            swal("Sucesso!", "Item removido", "success");
            window.location.reload();
            }
        },
        error: function(err) {
            var erro = err.responseJSON
            swal("Atenção!", erro.error, "error");
        }
    });

    
    }
    });
    })

</script>
@endsection







  