@extends('layouts.app')
@section('assets')
@endsection
@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
   
        <div class="card">
            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-7 ps-4 my-2">
                        <h4>Permissoes</h4>          
                    </div>
                   
                    <!-- <div class="col-5 my-2 pe-4 text-end">
                    <a href="{{route('admin.produtos.new')}}" class="btn btn-primary " type="button" 
                     >Cadastrar</a>
                    </div> -->
                </div>


                <div id="lista-permissoes">
                    @include('admin.permissoes._list-permissoes')
                </div> 
                <div class="row mt-3 justify-content-center">
                        <div class="col-sm-12 mx-auto align-center">
                        {{ $usuarios->links() }}
                        </div>
                </div>
               
           </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>

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
function toggleCollapse(id) {
    var content = document.getElementById("collapseContent" + id);
    if (content.style.display === "none") {
        content.style.display = "block"; // Mostrar o conteúdo quando estiver oculto
    } else {
        content.style.display = "none"; // Ocultar o conteúdo quando estiver visível
    }
}
$("body").on('change', '.status-categoria', function (e) {
    e.preventDefault();
    var id = this.getAttribute('data-id');
    console.log("O estado do checkbox mudou! " + id);

    var url = '{{ route("admin.produtos.mudarStatus", ["id" => ":id"]) }}';
    url = url.replace(':id', id);
    $.get(url, function (data) {
        swal({
            title: "Parabéns",
            text: "Status alterado com sucesso!.",
            icon: "success",
        })
    });
});
$("body").on('change', '.status-categoria-detalhes', function (e) {
    e.preventDefault();
    var id = this.getAttribute('data-id');
    console.log("O estado do checkbox mudou! " + id);

    var url = '{{ route("admin.produtos.mudarStatusDetalhes", ["id" => ":id"]) }}';
    url = url.replace(':id', id);
    $.get(url, function (data) {
        swal({
            title: "Parabéns",
            text: "Status alterado com sucesso!.",
            icon: "success",
        })
    });
});

</script>
@endsection







  