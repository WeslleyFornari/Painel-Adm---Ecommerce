

<!-- MULTIPLE BAR GOOGLE VRTICAL -->
<script>
    window.onload = function() {
        graficos();
        graficos2();
    };

    function graficos(){
        var franquia = $("#franqueado").val();
        if (franquia === 'todos') {
            graficosQtdPedidos(franquia);
            graficosValorPedidos(franquia);
            graficosValorPedidosTodos();
            graficosQtdPedidosTodos();
            graficosPedidoSemanaTodos();
            graficosFranquiaPorDia();
            graficosTicketMedioTodos();
            google.charts.load('current', {packages: ['corechart', 'line']});
            google.charts.setOnLoadCallback(function() {
                GraficoTicketMedio(franquia); 
            });

            PedidoSemana(franquia);
            graficosProdutosMaisAlugados(franquia);
            GraficoFaturamento(franquia);
            $(".bar_Valor_todos").show();
            $(".bar_Qtd_todos").show();
            $(".weekChartTodos").show();
            $(".weekChart2").show();
            $(".ticket_medio_todos").show();
        }
        else{
            PedidoSemana(franquia);
            graficosQtdPedidos(franquia);
            graficosValorPedidos(franquia);
            google.charts.load('current', {packages: ['corechart', 'line']});
            google.charts.setOnLoadCallback(function() {
                GraficoTicketMedio(franquia); 
            });
            graficosProdutosMaisAlugados(franquia);
            GraficoFaturamento(franquia);
            $(".bar_Valor_todos").hide();
            $(".bar_Qtd_todos").hide();
            $(".weekChartTodos").hide();
            $(".weekChart2").hide();
            $(".ticket_medio_todos").hide();
        }
    }
    $("body").on('change', '#franqueado', function (e) {
        e.preventDefault();
        window.weekChart.destroy();
        window.barChart2.destroy();
        graficos();
    });
    $("body").on('change', '#data_franquia', function (e) {
        e.preventDefault();
        window.weekChart2.destroy();
        graficosFranquiaPorDia();
    });

    function graficosQtdPedidos(franquia) {
        var url = '{{ route("admin.dashboard.graficosQtdPedidos", ["franquia" => ":franquia"]) }}';
        url = url.replace(':franquia', franquia);
        $.ajax({
            url: url,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                const chartData = [['MONTH', 'Realizados', 'Carrinhos']];
                data.forEach(pedidos => {
                    chartData.push([pedidos.month, pedidos.qtd, pedidos.carrinhos]);
                });

                var googleData = google.visualization.arrayToDataTable(chartData);

                var options = {
                    title: 'Gráfico Quantidade de Pedidos (Últimos 12 meses)',
                    vAxis: {title: 'Quantidade'},
                    hAxis: {title: 'Meses'},
                    seriesType: 'bars',
                };

                var chart = new google.visualization.ComboChart(document.getElementById('bar_Qtd'));
                chart.draw(googleData, options);
            },
            error: function(xhr, status, error) {
                console.error('Erro ao carregar dados:', error);
            }
        });
    }
    function graficosValorPedidos(franquia) {
        var url = '{{ route("admin.dashboard.graficosQtdPedidos", ["franquia" => ":franquia"]) }}';
        url = url.replace(':franquia', franquia);
        $.ajax({
            url: url,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                const chartData = [['MONTH', 'Pagos', 'Pendentes']];
                data.forEach(pedidos => {
                    chartData.push([pedidos.month, pedidos.valor_pagos, pedidos.valor_pendentes]);
                });

                var googleData = google.visualization.arrayToDataTable(chartData);

                var options = {
                    title: 'Gráfico Valores dos Pedidos (Últimos 12 meses)',
                    vAxis: {title: 'Quantidade'},
                    hAxis: {title: 'Meses'},
                    seriesType: 'bars',
                };

                var chart = new google.visualization.ComboChart(document.getElementById('bar_Valor'));
                chart.draw(googleData, options);
            },
            error: function(xhr, status, error) {
                console.error('Erro ao carregar dados:', error);
            }
        });
    }
   

    function graficosValorPedidosTodos() {
        $.ajax({
            url: '{{route("admin.dashboard.graficosValorPedidosTodos")}}',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                const chartData = [['MONTH', ...response.franquias.map(f => f.nome)]];

                response.data.forEach(monthData => {
                    const row = [monthData.month];
                    response.franquias.forEach(franquia => {
                        const franquiaData = monthData.franquias.find(f => f.nome_franquia === franquia.nome);
                        row.push(franquiaData ? franquiaData.valor_pagos : 0);
                    });
                    chartData.push(row);
                });

                const googleData = google.visualization.arrayToDataTable(chartData);

                const options = {
                    title: 'Gráfico Valores dos Pedidos Pagos Por Franquias (Últimos 12 meses)',
                    vAxis: { title: 'Valores' },
                    hAxis: { title: 'Meses' },
                    seriesType: 'bars',
                    series: { 
                        0: { type: 'bars', color: '#1C86EE' },
                        1: { type: 'bars', color: '#6959CD' }
                    }
                };

                const chart = new google.visualization.ComboChart(document.getElementById('bar_Valor_todos'));
                chart.draw(googleData, options);
            },
            error: function(xhr, status, error) {
                console.error('Erro ao carregar dados:', error);
            }
        });
    }
    function graficosQtdPedidosTodos() {
        $.ajax({
            url: '{{route("admin.dashboard.graficosValorPedidosTodos")}}',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                const chartData = [['MONTH', ...response.franquias.map(f => f.nome)]];

                response.data.forEach(monthData => {
                    const row = [monthData.month];
                    response.franquias.forEach(franquia => {
                        const franquiaData = monthData.franquias.find(f => f.nome_franquia === franquia.nome);
                        row.push(franquiaData ? franquiaData.qtd : 0);
                    });
                    chartData.push(row);
                });

                const googleData = google.visualization.arrayToDataTable(chartData);

                const options = {
                    title: 'Gráfico Qtd de Pedidos Por Franquias (Últimos 12 meses)',
                    vAxis: { title: 'Valores' },
                    hAxis: { title: 'Meses' },
                    seriesType: 'bars',
                    series: { 
                        0: { type: 'bars', color: '#1C86EE' },
                        1: { type: 'bars', color: '#6959CD' }
                    }
                };

                const chart = new google.visualization.ComboChart(document.getElementById('bar_Qtd_todos'));
                chart.draw(googleData, options);
            },
            error: function(xhr, status, error) {
                console.error('Erro ao carregar dados:', error);
            }
        });
    }


</script>
<script>
    function PedidoSemana(franquia) {
        var url = '{{ route("admin.dashboard.graficosPedidoSemana", ["franquia" => ":franquia"]) }}';
        url = url.replace(':franquia', franquia);
        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                var ctx2 = document.getElementById('weekChart').getContext('2d');

                window.weekChart = new Chart(ctx2, {
                    type: 'bar',
                    data: {
                        labels: response.map(data => data.day),
                        datasets: [{
                            label: 'Pedidos por dia',
                            data: response.map(data => data.qtd),
                            backgroundColor: 'rgba(153, 102, 255, 0.2)',
                            borderColor: 'rgba(153, 102, 255, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value.toFixed(0); // Remove casas decimais no eixo y
                                    }
                                }
                            }
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: 'Pedidos por Dia da Semana',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            label += context.parsed.y.toFixed(0); // Limita a 2 casas decimais nas tooltips
                                        }
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error('Erro ao obter os dados:', error);
            }
        });
    }
        
    function graficosPedidoSemanaTodos() {
        $.ajax({
            url: '{{route("admin.dashboard.graficosPedidoSemanaTodos")}}',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                const labels = response.data.map(dados => dados.semana);
                
                // Lista de cores distintas
                const colors = [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(199, 199, 199, 0.2)',
                    'rgba(83, 102, 255, 0.2)'
                ];

                const borderColor = [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(199, 199, 199, 1)',
                    'rgba(83, 102, 255, 1)'
                ];

                const datasets = response.franquias.map((franquia, index) => {
                    return {
                        label: franquia.nome,
                        data: response.data.map(monthData => {
                            const franquiaData = monthData.franquias.find(f => f.nome_franquia === franquia.nome);
                            return franquiaData ? franquiaData.qtd : 0;
                        }),
                        backgroundColor: colors[index % colors.length],
                        borderColor: borderColor[index % borderColor.length],
                        borderWidth: 1
                    };
                });

                // Configuração do gráfico Chart.js
                const ctx = document.getElementById('weekChartTodos').getContext('2d');
                const myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: datasets
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value.toFixed(0); // Remove casas decimais
                                    }
                                }
                            }
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: 'Pedidos por Dia da Semana Por Franquias',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            label += 'R$' + context.parsed.y.toFixed(2); // Limita a 2 casas decimais nas tooltips
                                        }
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error('Erro ao carregar dados:', error);
            }
        });
    }

</script>

<!-- BAR SIMPLES TICKET MEDIO--> 
<script>
    function graficosTicketMedioTodos() {
        $.ajax({
            url: '{{route("admin.dashboard.graficosValorPedidosTodos")}}',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                const labels = response.data.map(monthData => monthData.month);
                
                // Lista de cores distintas
                const colors = [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(199, 199, 199, 0.2)',
                    'rgba(83, 102, 255, 0.2)'
                ];

                const borderColor = [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(199, 199, 199, 1)',
                    'rgba(83, 102, 255, 1)'
                ];

                const datasets = response.franquias.map((franquia, index) => {
                    return {
                        label: franquia.nome,
                        data: response.data.map(monthData => {
                            const franquiaData = monthData.franquias.find(f => f.nome_franquia === franquia.nome);
                            return franquiaData ? franquiaData.ticket_medio : 0;
                        }),
                        backgroundColor: colors[index % colors.length],
                        borderColor: borderColor[index % borderColor.length],
                        borderWidth: 1
                    };
                });

                // Configuração do gráfico Chart.js
                const ctx = document.getElementById('ticket_medio_todos').getContext('2d');
                const myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: datasets
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value.toFixed(0); // Remove casas decimais
                                    }
                                }
                            }
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: 'Ticket Médio Por Franquias (ùltimo 12 meses)',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            label += 'R$' + context.parsed.y.toFixed(2); // Limita a 2 casas decimais nas tooltips
                                        }
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error('Erro ao carregar dados:', error);
            }
        });
    }
</script>

<script>
function graficosFranquiaPorDia() {
    var data = $('#data_franquia').val();
    console.log(data);
    var url = '{{ route("admin.dashboard.graficosFranquiaPorDia", ["data" => ":data"]) }}';
    url = url.replace(':data', data);
    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            var ctx2 = document.getElementById('weekChart2').getContext('2d');
            window.weekChart2 = new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: response.labels,
                    datasets: [{
                        label: 'Qtd de Pedidos',
                        data: response.qtd,
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toFixed(0); // Remove casas decimais no eixo y
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false // Desativa a exibição da legenda
                        },
                        title: {
                            display: true,
                            text: 'Qtd de Pedidos Por Franquias (Por Dia)',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += context.parsed.y.toFixed(0); // Limita a 2 casas decimais nas tooltips
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        },
        error: function(xhr, status, error) {
            console.error('Erro ao carregar os dados:', error);
        }
    });
};

</script>

<!-- DONUT 3D GOOGLE -->
<script>

function graficosProdutosMaisAlugados(franquia) {
    var url = '{{ route("admin.dashboard.graficosProdutosMaisAlugados", ["franquia" => ":franquia"]) }}';
    url = url.replace(':franquia', franquia);

    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        success: function(response) {

            var data = google.visualization.arrayToDataTable(response);

            var options = {
                title: 'TOP 5 - Produtos mais alugados',
                titleTextStyle: {
                    color: 'gray',
                    fontSize: 16
                },
                pieHole: 0.4,
            };

            var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
            chart.draw(data, options);
        },
        error: function(xhr, status, error) {
            console.error('Erro ao carregar dados:', error);
        }
    });
}

      
</script>
<!-- Grafico de linhas - Ticket Medio -->
<script>
    function GraficoTicketMedio(franquia) {
    var url = '{{ route("admin.dashboard.graficosQtdPedidos", ["franquia" => ":franquia"]) }}';
    url = url.replace(':franquia', franquia);

    $.ajax({
        url: url,
        method: 'GET',
        success: function(response) {

            var dataArray = [['Mês', 'Ticket Médio']];
            response.forEach(function(item) {
                dataArray.push([item.month, item.ticket_medio]);
            });

            // Certifique-se de que google.visualization foi carregado
            if (google.visualization) {
                var data = google.visualization.arrayToDataTable(dataArray);

                var options = {
                    title: 'Ticket Médio',
                    curveType: 'none',
                    legend: { position: 'bottom' },
                    vAxis: {
                        title: 'Ticket Médio'
                    },
                    series: {
                        0: {
                            pointSize: 5,
                            pointsVisible: true
                        }
                    }
                };

                var chart = new google.visualization.LineChart(document.getElementById('line_chart'));
                chart.draw(data, options);
            } else {
                console.error('Google Visualization API not loaded.');
            }
        },
        error: function(xhr, status, error) {
            console.error('Erro ao obter os dados:', error);
        }
    });
}
</script>


    
<!-- BAR SIMPLES FATURAMENTO MENSAL--> 
<script>

function GraficoFaturamento(franquia) {
        var url = '{{ route("admin.dashboard.graficosQtdPedidos", ["franquia" => ":franquia"]) }}';
        url = url.replace(':franquia', franquia);
        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                var ctx = document.getElementById('barChart2').getContext('2d');

                window.barChart2 = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: response.map(data => data.month),
                        datasets: [{
                            label: 'Valor Faturamento',
                            data: response.map(data => data.valor_pagos),
                            backgroundColor: 'rgba(255, 159, 64, 0.2)', // Cor de fundo laranja
                            borderColor: 'rgba(255, 159, 64, 1)', // Cor da borda laranja
                            borderWidth: 1
                        }]
                    },

                    options: {

                        layout: {
                            padding: {
                                bottom: 50 // Aumenta a margem inferior
                            }
                        },


                        scales: {
                            y: {
                            beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value.toFixed(0); // Remove casas decimais
                                    }
                                }
                            }
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: 'Faturamento Mensal (ùltimo 12 meses)',
                            },

                            legend: {
                            display: true,
                                labels: {
                                    font: {
                                        size: 16 // Aumenta o tamanho da fonte do rótulo
                                    },
                                    padding: 0 // Aumenta o espaçamento do rótulo
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            label += 'R$' + context.parsed.y.toFixed(2); // Limita a 2 casas decimais nas tooltips
                                        }
                                        return label;
                                    }
                                }
                            },
                        }
                    }

                });
            },
            error: function(xhr, status, error) {
                console.error('Erro ao obter os dados:', error);
            }
        });
    }
</script>

<!-- // Plataforma X Manual -->
<script>
function graficos2(){

        var pedMensal = @json($pedMensal);

        var manualData = [];
        var automaticoData = [];

        Object.keys(pedMensal).forEach(function(mes) {
            manualData.push({ label: mes, y: pedMensal[mes].manual });
            automaticoData.push({ label: mes, y: pedMensal[mes].automatico });
        });

        var chart = new CanvasJS.Chart("pedMensal", {
            animationEnabled: true,
            title:{
                text: "Pedidos - Últimos 6 Meses",
                fontSize: 18,
                fontFamily: "Arial",
                fontWeight: "bold" 

            },	
            axisY: {
                title: "Quantidade de Pedidos",
                titleFontColor: "#000000",
                lineColor: "#000000",
                labelFontColor: "#000000",
                tickColor: "#000000"
            },
            toolTip: {
                shared: true
            },
            legend: {
                cursor:"pointer",
                itemclick: toggleDataSeries
            },
            data: [{
                type: "column",
                name: "Pedidos Manuais",
                legendText: "Manuais",
                showInLegend: true, 
                dataPoints: manualData
            },
            {
                type: "column",	
                name: "Pedidos Automáticos",
                legendText: "Automáticos",
                showInLegend: true,
                dataPoints: automaticoData
            }]
        });

        chart.render();

        function toggleDataSeries(e) {
            if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                e.dataSeries.visible = false;
            } else {
                e.dataSeries.visible = true;
            }
            chart.render();
        }    

        
    var pedAnual = @json($pedAnual);

    var chart2 = new CanvasJS.Chart("pedGeral", {
    animationEnabled: true,
    title: {
        text: "Acumulativo",
        fontSize: 18,
        fontFamily: "Arial",
        fontWeight: "bold" 
    },	
    axisY: {
        title: "Quantidade de Pedidos",
        titleFontColor: "#000000",
        lineColor: "#000000",
        labelFontColor: "#000000",
        tickColor: "#000000"
    },
    toolTip: {
        shared: true
    },
    legend: {
        cursor: "pointer",
        itemclick: toggleDataSeries
    },
    data: [{
        type: "column",
        name: "Pedidos Manuais",
        legendText: "Manuais",
        showInLegend: true, 
        dataPoints: [{ label: "Manuais", y: pedAnual.manual }]
    },
    {
        type: "column",	
        name: "Pedidos Automáticos",
        legendText: "Automáticos",
        showInLegend: true,
        dataPoints: [{ label: "Automáticos", y: pedAnual.automatico }]
    }]
});

    chart2.render();

    function toggleDataSeries(e) {
        if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
            e.dataSeries.visible = false;
        } else {
            e.dataSeries.visible = true;
        }
        chart2.render();
    }

}

</script>







