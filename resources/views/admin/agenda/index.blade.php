@extends('layouts.app')
@section('assets')
@endsection
@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">

        <div class="card">
            <div class="card-body">

                <div class="row mb-1">
                    <div class="col-8 ps-4 my-2">
                        <h4>Agenda</h4>
                    </div>
                    <hr>
                </div>

                <div id="lista-Produtos">
                    <div class="row bg-dark arial16-font text-light text-bold m-0 py-2">
                        <div class="col-1">Tipo</div>
                        <div class="col-3">Nome</div>
                        <div class="col-3">Responsável</div>
                        <div class="col text-center">Agenda</div>
                    </div>

                    @foreach($franquias as $key => $value)
                    <div class="row m-0 py-2 border-bottom arial14-font-normal align-items-center">

                        <div class="col-1">{{ $value->tipo_franqueado ?? ''}}</div>
                        <div class="col-3">{{ $value->nome_franquia ?? '' }}</div>
                        <div class="col-3">{{ $value->nome_responsavel ?? '' }}</div>
                        <div class="col text-center">
                        @if(permissao('agenda')->criar == 'sim')
                            <a href="{{ route('admin.agenda.bloqueios', $value->id) }}" class="btn btn-info btn-sm"><i class="far fa-calendar" style="font-size: 18px"></i></a>
                        @endif
                        </div>
                        
                    </div>
                    @endforeach     

                    <div class="row mt-5 justify-content-center">
                        <div class="col-sm-12 mx-auto align-center">
                        {{ $franquias->links() }}  
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
 
</div>
@endsection

@section('scripts')
<!-- Include flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- Include flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- Include flatpickr rangePlugin JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/rangePlugin.js"></script>


<script>
var cont = 0;
$("body").on('click', '.adicionarDiasBloqueados', function (e) {
    e.preventDefault();
    var DiasBloqueados = document.querySelector('#DiasBloqueadosview');
    var newDiv = document.createElement("div");
    newDiv.classList.add("col-12", "mt-2", "row");
    newDiv.innerHTML = `
        <div class="col-4">
            <span class="titulo">De</span><br>
            <input class="flatpickr flatpickr-input active form-control data_inicio" type="text" placeholder="Select Date.." data-id="rangePlugin" readonly="readonly" id="data_inicio_${cont}" data-cont="${cont}" name="datas[data_inicio][]">
        </div>
        <div class="col-4">
            <span class="titulo">Até</span><br>
            <input type="text" placeholder="Select Date.." class="form-control" readonly="readonly" id="data_fim_${cont}"  name="datas[data_fim][]">
        </div>
        <div class="col-4">
            <span class="titulo"></span><br>
            <a href="#" class="btn btn-icon-only btn-danger close" onclick="excluirDiasBloqueados(this)"><i class="fas fa-trash" aria-hidden="true"></i></a>
        </div>
    `;
    DiasBloqueados.appendChild(newDiv);
    cont++;

    flatpickr(newDiv.querySelector('.flatpickr-input'), {
        plugins: [new rangePlugin({ input: newDiv.querySelector(`#data_fim_${cont - 1}`) })],
    });
});

function excluirDiasBloqueados(element) {
    element.parentNode.parentNode.remove();
}

$("body").on('change', '.data_inicio', function (e) {
    e.preventDefault();
    var cont = $(this).data('cont'); 
    const inputId = 'data_inicio_' + cont; 
    const inputEndId = 'data_fim_' + cont; 

    flatpickr(document.getElementById(inputId), {
        plugins: [new rangePlugin({
            input: document.getElementById(inputEndId)
        })],
        onChange: function(selectedDates, dateStr, instance) {
            if (selectedDates.length === 2) {
                const startDate = selectedDates[0];
                const endDate = selectedDates[1]; 
                console.log(`Período selecionado para o campo ${inputId}:`, startDate, endDate);
            }
        }
    });
});





</script>
@endsection