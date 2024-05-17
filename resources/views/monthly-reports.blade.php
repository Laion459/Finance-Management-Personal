<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios Mensais</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-900 text-white p-6">
    <div class="max-w-7xl mx-auto">
        
        <h1 class="text-3xl font-bold mb-8">Relatórios Mensais</h1>
        <div class="flex justify-center mt-8">
            <a href="{{ route('home') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Home</a>
        </div>
        <br>
        <hr><br>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <canvas id="totalChart"></canvas>
            </div>

            <div>
                <canvas id="yearlyTotalsChart"></canvas>
            </div>



        </div>

        <div class="flex justify-center mt-8">
            <h1>Gráficos Mensais</h1>
        </div>

        <br>
        <hr><br>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <canvas id="incomesByCategoryChart"></canvas>
            </div>
            <div>
                <canvas id="expensesByCategoryChart"></canvas>
            </div>
        </div>

        <br>
        <hr><br>

        <div class="flex justify-center mt-8">
            <h1>Gráficos Anuais</h1>
        </div>

        <br>
        <hr><br>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


            <div>
                <canvas id="yearlyIncomesByCategoryChart"></canvas>
            </div>
            <div>
                <canvas id="yearlyExpensesByCategoryChart"></canvas>

            </div>
        </div>
        <br>
        <hr><br>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script>
    // Obter os dados JSON da view
    const chartData = JSON.parse('{!! $chartData !!}');



    // Gráfico de Total de Entradas e Saídas
    const totalChartCanvas = document.getElementById('totalChart').getContext('2d');
    new Chart(totalChartCanvas, {
        type: 'doughnut',
        data: {
            labels: chartData.total.labels,
            datasets: [{
                label: 'Total (R$)',
                data: chartData.total.data,
                backgroundColor: ['#36a2eb', '#ff6384'],
            }]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: 'Total de Entradas e Saídas', // Define o título do gráfico
                    position: 'top' // Define a posição do título (top, bottom, left, right)
                }
            }
        }
    });



    // Gráfico de Despesas por Categoria
    const expensesByCategoryChartCanvas = document.getElementById('expensesByCategoryChart').getContext('2d');
    new Chart(expensesByCategoryChartCanvas, {
        type: 'bar',
        data: {
            labels: chartData.expensesByCategory.labels,
            datasets: [{
                label: 'Despesas por Categoria (R$)', // label do dataset
                data: chartData.expensesByCategory.data,
                backgroundColor: [
                    '#ff6384', '#36a2eb', '#ffcd56', '#4bc0c0', '#9966ff', '#ff9933'
                ]
            }]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: 'Distribuição de Saidas'
                },
                datalabels: {
                    // ...
                },
                legend: {
                    display: true,
                    position: 'bottom', // Move a legenda para baixo
                    labels: {

                    }
                }
            },
            scales: {
                x: {
                    ticks: {
                        display: true // Esconde os labels do eixo X
                    }
                },
                y: {
                    //display: true,
                    title: {
                        display: true // Esconde o label do eixo Y
                    }
                }
            }
        }
    });


    // Gráfico de Entradas por Categoria
    const incomesByCategoryChartCanvas = document.getElementById('incomesByCategoryChart').getContext('2d');
    new Chart(incomesByCategoryChartCanvas, {
        type: 'bar',
        data: {
            labels: chartData.incomesByCategory.labels,
            datasets: [{
                label: 'Entradas por Categoria (R$)',
                data: chartData.incomesByCategory.data,
                backgroundColor: [
                    '#36a2eb', '#ffcd56', '#4bc0c0', '#ff6384', '#9966ff', '#ff9933'
                ]
            }]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: 'Distribuição de Entradas'
                },
                datalabels: {
                    formatter: (value, context) => {
                        let sum = 0;
                        let dataArr = context.chart.data.datasets[0].data;
                        dataArr.map(data => {
                            sum += data;
                        });
                        let percentage = (value * 100 / sum).toFixed(2) + "%";
                        return percentage;
                    },
                    color: '#fff',
                    // Aqui que a função generateLabels deve ser usada
                    generateLabels: (chart) => {
                        const dataset = chart.data.datasets[0];
                        return dataset.data.map((value, index) => {
                            const percentage = ((value / dataset.data.reduce((a, b) => a + b, 0)) * 100).toFixed(2);
                            return {
                                text: `${chart.data.labels[index]} (${percentage}%)`,
                                fillStyle: dataset.backgroundColor[index], // Define a cor do label
                                hidden: !chart.getDatasetMeta(0).data[index].hidden // Mantém a visibilidade do label
                            };
                        });
                    }
                },
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        // Remova generateLabels daqui, use dentro de datalabels
                    }
                }
            }
        }
    });


    // Gráfico de Total de Entradas e Saídas por Ano
    const yearlyTotalsChartCanvas = document.getElementById('yearlyTotalsChart').getContext('2d');
    new Chart(yearlyTotalsChartCanvas, {
        type: 'bar',
        data: {
            labels: chartData.yearlyTotals.labels,
            datasets: [{
                    label: 'Entradas (R$)',
                    data: chartData.yearlyTotals.entradas,
                    backgroundColor: '#36a2eb'
                },
                {
                    label: 'Saídas (R$)',
                    data: chartData.yearlyTotals.saidas,
                    backgroundColor: '#ff6384'
                }
            ]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: 'Total de Entradas e Saídas Anual', // Define o título do gráfico
                    position: 'top' // Define a posição do título (top, bottom, left, right)
                }
            }
        }
    });



    // Gráfico de Total de Entradas Anual por Categoria
    const yearlyIncomesByCategoryChartCanvas = document.getElementById('yearlyIncomesByCategoryChart').getContext('2d');

    // Corrigindo a atribuição da variável:
    const yearlyIncomesByCategoryChart = new Chart(yearlyIncomesByCategoryChartCanvas, {
        type: 'bar',
        data: {
            labels: Object.keys(chartData.yearlyIncomesByCategory), // Anos
            datasets: []
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: 'Total de Entradas Anual por Categoria'
                }
            }
        }
    });

    // Adicionar datasets para cada categoria
    for (const categoria in chartData.yearlyIncomesByCategory[Object.keys(chartData.yearlyIncomesByCategory)[0]]) {
        yearlyIncomesByCategoryChart.data.datasets.push({
            label: categoria,
            data: Object.values(chartData.yearlyIncomesByCategory).map(ano => ano[categoria] || 0),
            backgroundColor: getRandomColor() // Função para gerar cores aleatórias
        });
    }
    yearlyIncomesByCategoryChart.update();

    // Função para gerar cores aleatórias
    function getRandomColor() {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }


    // Gráfico de Total de Saídas Anual por Categoria
    const yearlyExpensesByCategoryChartCanvas = document.getElementById('yearlyExpensesByCategoryChart').getContext('2d');
    const yearlyExpensesByCategoryChart = new Chart(yearlyExpensesByCategoryChartCanvas, {
        type: 'bar', // ou 'line'
        data: {
            labels: Object.keys(chartData.yearlyExpensesByCategory), // Anos
            datasets: []
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: 'Total de Saídas Anual por Categoria'
                }
            }
        }
    });

    // Adicionar datasets para cada categoria
    for (const categoria in chartData.yearlyExpensesByCategory[Object.keys(chartData.yearlyExpensesByCategory)[0]]) {
        yearlyExpensesByCategoryChart.data.datasets.push({
            label: categoria,
            data: Object.values(chartData.yearlyExpensesByCategory).map(ano => ano[categoria] || 0),
            backgroundColor: getRandomColor()
        });
    }
    yearlyExpensesByCategoryChart.update();
</script>

</html>
