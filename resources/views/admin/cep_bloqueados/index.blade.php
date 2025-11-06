@extends('layouts.app')

@section ('assets')

<style>
    nav .justify-between{
        display: none;
    }

    nav .hidden .relative a svg{
        width: 2% !important;
    }
    nav .hidden .relative span svg{
        width: 2% !important;
    }

    .hidden .relative span span{
        background: #ed3237 !important;
        color: #fff;
    }

    .hidden .relative span .rounded-l-md{
        background: #fff !important;
        color: #67748E;
    }

    .hidden .relative span .rounded-r-md{
        background: #fff !important;
        color: #67748E;
    }

    .icon i{
        font-size: 15px;
        color: #ed3237 !important;
    }

@media (max-width: 950px) {
    nav .hidden .relative a svg{
        width: 10% !important;
    }
    nav .hidden .relative span svg{
        width: 10% !important;
    }
}

</style>
@endsection
@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
   
        <div class="card">
            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-7 ps-4 my-2">
                        <h4>CEPs Bloqueados</h4>          
                    </div>
                    @if(permissao('cep_bloqueados')->criar == 'sim')
                    <div class="col-5 my-2 pe-4 text-end">
                        <a href="{{route('admin.cep_bloqueados.cadastro')}}" class="btn btn-primary">Cadastrar </a>
                    </div>
                    @endif
                </div>
                <div id="lista-Produtos">
                    @include('admin.cep_bloqueados._list')
                </div> 
                {{ $cep_bloqueados->links() }}
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

    var url = '{{ route("admin.cep_bloqueados.mudarStatus", ["id" => ":id"]) }}';
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







  