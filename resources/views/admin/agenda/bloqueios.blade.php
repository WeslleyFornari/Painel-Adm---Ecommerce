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
                    <form action="{{ route('admin.agenda.store') }}" id="formStore" method="POST" enctype="multipart/form-data">
                    @csrf

                        <input type="hidden" name="id_franquia" value="{{$franquia->id}}">
                        <div class="row">
                            <div class="col-6 my-2">
                                <h5>Dias Bloqueados</h5>    
                                @php
                                    $cont = 0;
                                @endphp
                                @if ($diasBloqueados)
                                @foreach ($diasBloqueados as $dia)
                                <div class="col-12 mt-2 row dias">
                                    <div class="col-4">
                                        <span class="titulo">De</span><br>
                                        <input class="flatpickr flatpickr-input active form-control data_inicio" type="text" placeholder="Select Date.." data-id="rangePlugin" readonly="readonly" id="data_inicio_{{$cont}}" data-cont="{{$cont}}" name="datas[data_inicio][]" value="{{ $dia['data_inicio'] }}">
                                    </div>
                                    <div class="col-4">
                                        <span class="titulo">Até</span><br>
                                        <input type="text" placeholder="Select Date.." class="form-control" readonly="readonly" id="data_fim_{{$cont}}"  name="datas[data_fim][]" value="{{ $dia['data_fim'] }}">
                                    </div>
                                    <div class="col-4">
                                        <span class="titulo"></span><br>
                                        <a href="#" class="btn btn-icon-only btn-danger close" onclick="excluirDiasBloqueados(this)"><i class="fas fa-trash" aria-hidden="true"></i></a>
                                    </div>
                                </div>
                                @endforeach
                                @endif
                                <div class="" id="DiasBloqueadosview"></div>
                                <a href="#" class="btn btn-sm btn-primary adicionarDiasBloqueados" style="font-size: 15px"><i class="fas fa-plus-circle" style="font-size: 15px"></i> Adicionar</a>     
                            </div>

                            <div class="col-6">
                                <h5>Dias da Semana Bloqueados</h5>    
                                <label>
                                    <input type="checkbox"  name="datas_semana[dia_semana][]" value="0" @if(agenda_dia_semana($franquia, 0)) checked @endif> Domingo
                                </label><br>
                                <label>
                                    <input type="checkbox" name="datas_semana[dia_semana][]" value="1" @if(agenda_dia_semana($franquia, 1)) checked @endif> Segunda-feira
                                </label><br>
                                <label>
                                    <input type="checkbox" name="datas_semana[dia_semana][]" value="2" @if(agenda_dia_semana($franquia, 2)) checked @endif> Terça-feira
                                </label><br>
                                <label>
                                    <input type="checkbox" name="datas_semana[dia_semana][]" value="3" @if(agenda_dia_semana($franquia, 3)) checked @endif> Quarta-feira
                                </label><br>
                                <label>
                                    <input type="checkbox" name="datas_semana[dia_semana][]" value="4" @if(agenda_dia_semana($franquia, 4)) checked @endif> Quinta-feira
                                </label><br>
                                <label>
                                    <input type="checkbox" name="datas_semana[dia_semana][]" value="5" @if(agenda_dia_semana($franquia, 5)) checked @endif> Sexta-feira
                                </label><br>
                                <label>
                                    <input type="checkbox" name="datas_semana[dia_semana][]" value="6" @if(agenda_dia_semana($franquia, 6)) checked @endif> Sábado
                                </label><br>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-end">
                                <button type="submit" class="btn btn-success">Salvar</button>
                            </div>
                        </div>
                    </form>
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
var cont = document.querySelectorAll('.dias');
$("body").on('click', '.adicionarDiasBloqueados', function (e) {
    e.preventDefault();
    cont++;
    var DiasBloqueados = document.querySelector('#DiasBloqueadosview');
    var newDiv = document.createElement("div");
    newDiv.classList.add("col-12", "mt-2", "row", "dias");
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

$("#formStore").submit(function(e) {
    e.preventDefault();
    $("span.error").remove();
    $.ajax({
        type: "POST",
        url: $(this).attr('action'),
        data: $(this).serialize(),
        success: function(data) {
            swal({
                title: "Parabéns",
                text: "Cadastro realizado com sucesso!.",
                icon: "success",
            }).then(function() {
                location.reload();
            });
            $(".disabled").remove();
        },
        error: function(err) {
            console.log(err);
            if (err.status == 422) {
                console.log(err.responseJSON);
                $('#success_message').fadeIn().html(err.responseJSON.message);
                $.each(err.responseJSON.errors, function(i, error) {
                    var el = $(document).find('[name="' + i + '"]');
                    el.after($('<span class="error" style="color: red;">' + error[0] + '</span>'));
                });
            }
        }
    });
});




</script>
@endsection