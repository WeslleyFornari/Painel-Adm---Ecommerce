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
            var weekChart2 = new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: response.labels,
                    datasets: [{
                        label: 'Pedidos por dia',
                        data: response.data,
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