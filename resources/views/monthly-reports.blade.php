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
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <canvas id="totalChart"></canvas>
            </div>
            <div>
                <canvas id="dailyExpensesChart"></canvas>
            </div>
            <div>
                <canvas id="expensesByCategoryChart"></canvas>
            </div>
            <div>
                <canvas id="incomesByCategoryChart"></canvas>
            </div>
        </div>
        <div class="flex justify-center mt-8">
            <a href="{{ route('home') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Home</a>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script>
    // Obter os dados JSON da view
    const chartData = JSON.parse('{!! $chartData !!}');

    // Verificar se chartData.dailyExpenses existe e não é vazio
    if (chartData.dailyExpenses && Object.keys(chartData.dailyExpenses).length > 0) {
        const labels = Object.keys(chartData.dailyExpenses);
        const data = Object.values(chartData.dailyExpenses);


        // Gráfico de Despesas Diárias
        const dailyExpensesChartCanvas = document.getElementById('dailyExpensesChart').getContext('2d');
        new Chart(dailyExpensesChartCanvas, {
            type: 'doughnut',
            data: {
                labels: chartData.dailyExpenses.labels,
                datasets: [{
                    label: 'Despesas Diárias (R$)',
                    data: chartData.dailyExpenses.data,
                    borderColor: '#ffcd56',
                    fill: false
                }]
            }
        });
    } else {
        // Tratar o caso em que chartData.dailyExpenses é undefined, null ou vazio
        console.error("Dados para dailyExpensesChart não disponíveis.");
    }

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
        }
    });



    // Gráfico de Despesas por Categoria
    const expensesByCategoryChartCanvas = document.getElementById('expensesByCategoryChart').getContext('2d');
    new Chart(expensesByCategoryChartCanvas, {
        type: 'bar',
    data: {
        // Usar Object.keys para obter as categorias (Tipo - Subtipo)
        labels: Object.keys(chartData.expensesByCategory),
        datasets: [{
            label: 'Despesas por Categoria (R$)',
            // Usar Object.values para obter os valores correspondentes
            data: Object.values(chartData.expensesByCategory),
            backgroundColor: [
                '#ff6384', '#36a2eb', '#ffcd56', '#4bc0c0', '#9966ff', '#ff9933'
            ]
        }]
    },
        plugins: ['datalabels'],
        options: {
            plugins: {
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
</script>

</html>
