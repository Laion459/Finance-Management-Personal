<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de Orçamento</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-900 text-white p-6">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">Controle de Orçamento</h1>
        <div class="flex justify-center mt-8">
            <a href="{{ route('home') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Home</a>
        </div>
        <br>
        <hr><br>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <div class="bg-gray-800 rounded-lg p-6">
                    <h1 class="text-xl font-semibold mb-4">Total Gasto no Último Mês (R$)</h1>
                    <p>{{ number_format($totalDespesasMes, 2, ',', '.') }}</p>
                </div>


                <hr><br>
                <div class="bg-gray-800 rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-4">Relatório Mensal (Últimos 7 Dias)</h2>
                    <canvas id="relatorioMensalChart"></canvas>
                </div>



            </div>

            <div class="bg-gray-800 rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Últimas Compras</h2>
                <ul>
                    @foreach ($ultimasCompras as $compra)
                    <li class="border-b border-gray-700 py-2">
                        {{ $compra->created_at->format('d/m/Y') }} -
                        {{ $compra->tipo_despesa }} -
                        {{ $compra->categoria }} -
                        {{ $compra->descricao ? $compra->descricao : '' }} -
                        R$ {{ number_format($compra->valor, 2, ',', '.') }}
                    </li>
                    @endforeach
                </ul>

            </div>
            <hr>

            <div class="grid grid-cols-1 gap-6">
                <hr>

                <div class="bg-gray-800 rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-4">Gráfico de Entradas e Saidas Último Mês</h2>
                    <canvas id="totalChart"></canvas>

                </div>




            </div>

        </div>


    </div>

    <script>
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
                        position: 'bottom' // Define a posição do título (top, bottom, left, right)
                    }
                }
            }
        });



        // Relatório  Mensal de entradas e saidas dos (Últimos 7 Dias)
        const relatorioMensalChartCanvas = document.getElementById('relatorioMensalChart').getContext('2d');
        new Chart(relatorioMensalChartCanvas, {
            type: 'bar',
            data: {
                labels: chartData.relatorioMensal.labels,
                datasets: [{
                        label: 'Entradas',
                        data: chartData.relatorioMensal.entradas,
                        backgroundColor: '#36a2eb'
                    },
                    {
                        label: 'Saídas',
                        data: chartData.relatorioMensal.saidas,
                        backgroundColor: '#ff6384'
                    }
                ]
            }
        });



        // Gráfico de Entradas e Saídas Último Mês
        const totalUltimoMesChartCanvas = document.getElementById('totalUltimoMesChart').getContext('2d');
        new Chart(totalUltimoMesChartCanvas, {
            type: 'line',
            data: {
                labels: ['Entradas', 'Saídas'],
                datasets: [{
                    label: 'Total',
                    data: chartData.totalUltimoMes.data,
                    backgroundColor: chartData.totalUltimoMes.backgroundColor
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

</body>

</html>
