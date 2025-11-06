@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">

            <div class="card mb-4 bg-light2222 shadow-sm">
            <div class="card-body">

            <!-- TAB Menu-->   
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Gráfico de Pedidos</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Entregas</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Devoluções</button>
                    </li>
                    <!-- <li class="nav-item" role="presentation">
                        <button class="nav-link" id="disabled-tab" data-bs-toggle="tab" data-bs-target="#disabled-tab-pane" type="button" role="tab" aria-controls="disabled-tab-pane" aria-selected="false" disabled>Últimos Orçamentos</button>
                    </li> -->
            </ul>
            <!-- TAB Conteudo--> 
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">@include('admin.dashboard.include._grafico-pedidos')</div>
                    <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">@include('admin.dashboard.include._entregas')</div>
                    <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">@include('admin.dashboard.include._devolucoes')</div>
                    <!-- <div class="tab-pane fade" id="disabled-tab-pane" role="tabpanel" aria-labelledby="disabled-tab" tabindex="0">...</div>          -->
                </div>

            </div>
            </div>

        </div>
    </div>
</div>

@endsection


@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>

<script>
$("body").on('click', '.paginacaoEntregas .pagination a', function(event) {
    event.preventDefault(); 

    var url = $(this).attr('href'); 
    fetchEntregas(url); 
});


function fetchEntregas(url = '{{route("admin.getEntregas")}}' ) {
    $.ajax({
        url: url,
        method: 'GET',
        beforeSend: function() {
            $('.lista-Entregas').html('<div class="text-center">Carregando...</div>');
        },
        success: function(data) {
            $('.lista-Entregas').html(data); 
        },
        error: function() {
            alert('Erro ao carregar as entregas.');
        }
    });
}



$("body").on('click', '.paginacaoDevolucoes .pagination a', function(event) {
    event.preventDefault(); 

    var url = $(this).attr('href'); 
    fetchDevolucoes(url); 
});


function fetchDevolucoes(url = '{{route("admin.getDevolucoes")}}' ) {
    $.ajax({
        url: url,
        method: 'GET',
        beforeSend: function() {
            $('.lista-Devolucoes').html('<div class="text-center">Carregando...</div>');
        },
        success: function(data) {
            $('.lista-Devolucoes').html(data); 
        },
        error: function() {
            alert('Erro ao carregar as devoluções.');
        }
    });
}

fetchEntregas();
fetchDevolucoes();

// Flatpickr +7 dias
    $("body").on('change', 'input[name=data_inicial]', function (e) {

        e.preventDefault();
        var data_inicial = $(this).val();
    
        var minDate = new Date(data_inicial);
        minDate.setDate(minDate.getDate() + 8);

        $("input[name=data_final]").flatpickr({
            altInput: true,
            altFormat: "d/m/Y",
            dateFormat: "Y-m-d",
            locale: "pt",
            minDate: minDate,
            disable: [
                function(date) {
                    return (date.getDay() === 0);
                }
            ]
        });
    });

// Filtrar
$('#filtrar-entregas input').on('change', function() {

    var dataInicial = $('input[name="data_inicial"]').val();
    var dataFinal = $('input[name="data_final"]').val();
    var procurar = $('input[name="procurar"]').val();

    // Verifica se pelo menos um dos campos (datas ou buscar) foi preenchido
    if ((dataInicial && dataFinal) || procurar) {
        var formData = $('#filtrar-entregas').serialize();
        console.log(formData);

        $.ajax({
            url: $('#filtrar-entregas').attr('action'),
            method: 'POST',
            data: formData,

            success: function(response) {
                $('.lista-Entregas').html(response); 
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    }
});

// Procurar
    $('#filtrar-entregas input[name="procurar"]').on('keyup', function() {
        var procurar = $(this).val();

        // Verifica se o campo de busca foi preenchido
        if (procurar) {
            var formData = $('#filtrar-entregas').serialize();
            console.log(formData);

            $.ajax({
                url: $('#filtrar-entregas').attr('action'),
                method: 'POST',
                data: formData,

                success: function(response) {
                    $('.lista-Entregas').html(response); 
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        }
    });
    $('#filtrar-devolucoes input[name="procurar"]').on('keyup', function() {
        var procurar = $(this).val();

        // Verifica se o campo de busca foi preenchido
        if (procurar) {
            var formData = $('#filtrar-devolucoes').serialize();
            console.log(formData);

            $.ajax({
                url: $('#filtrar-devolucoes').attr('action'),
                method: 'POST',
                data: formData,

                success: function(response) {
                    $('.lista-Devolucoes').html(response); 
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        }
    });
    

// Limpar Filtro
        $('.limpar-filtro').on('click', function(e) {
            e.preventDefault();
            $("#filtrar-entregas")[0].reset();
            $("#filtrar-devolucoes")[0].reset();
            fetchEntregas();
            fetchDevolucoes();
        });

</script>

@include('admin.dashboard._script-charts')

@endsection









  