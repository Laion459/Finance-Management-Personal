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

    <hr><br><br><br>
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
                <br>


                <hr><br>
                <div class="bg-gray-800 rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-4">Relatório Mensal (Últimos 7 Dias)</h2>
                    <canvas id="relatorioMensalChart"></canvas>
                </div>


                <hr><br>

                <div class="bg-gray-800 rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-4">Gráfico de Entradas e Saídas de Hoje</h2>
                    <canvas id="entradasSaidasHojeChart"></canvas>
                </div>

            </div>

            <div class="grid grid-cols-0 md:grid-cols-1 gap-6">
                <div class="bg-gray-800 rounded-lg p-6 mt-0">
                    <h2 class="text-xl font-semibold mb-4">Últimas Compras</h2>
                    <ul id="lista-ultimas-compras">
                    </ul>

                </div>


                <hr>



                <div class="bg-gray-800 rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-4">Gráfico de Entradas e Saidas Último Mês</h2>
                    <canvas id="totalChart"></canvas>

                </div>
            </div>
            <hr>
            <hr>

            <div class="grid grid-cols-0 md:grid-cols-1 gap-6">
                <div class="bg-gray-800 rounded-lg p-6 mt-0">
                    <h2 class="text-xl font-semibold mb-4">Compras do Mês Atual até o Momento</h2>
                    <ul id="lista-compras-mes-atual">
                        @foreach($comprasUltimoMesPorDia as $compraDia)
                        <li class="mb-4">
                            <h3 class="text-lg font-semibold">{{ $compraDia['dia'] }}</h3>
                            <ul>
                                @foreach($compraDia['compras'] as $compra)
                                <li>
                                    {{ $compra->created_at->format('d/m/Y') }} -
                                    {{ $compra->tipo_despesa }} -
                                    {{ $compra->categoria }} -
                                    {{ $compra->description ?? '' }} -
                                    R$ {{ number_format($compra->valor, 2, ',', '.') }}
                                </li>
                                @endforeach
                            </ul>
                        </li>
                        @endforeach
                    </ul>
                </div>



            </div>


        </div>


        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
        <script>
            const chartData = JSON.parse('{!! $chartData !!}');
            console.log(chartData)



            /// Mapeamento de IDs de categoria para nomes de categoria
            const categoriaMap = {
                1: 'PIX',
                2: 'Crédito',
                3: 'Débito',
                4: 'Dinheiro',
                5: 'Outro',
                6: 'Salário',
                7: 'Investimento',
                8: 'Empréstimo',
                9: 'Outros',
                10: 'Moradia',
                11: 'Transporte',
                12: 'Alimentação',
                13: 'Saúde',
                14: 'Educação',
                15: 'Lazer'
                // Adicione mais mapeamentos conforme necessário
            };

            // Preencher a lista inicialmente:
            chartData.listaUltimasCompras.forEach(function(compra) {
                // Obtendo o nome real da categoria usando o mapeamento
                let nomeCategoria = categoriaMap[compra.category_id] || 'Categoria Desconhecida';

                // Formatar a data como dia/mês/ano:
                let dataFormatada = `${new Date(compra.created_at).getDate()}/${new Date(compra.created_at).getMonth() + 1}/${new Date(compra.created_at).getFullYear()}`;

                let itemLista = `<li class="border-b border-gray-700 py-2">
                        ${dataFormatada} -
                        ${compra.tipo_despesa} -
                        ${nomeCategoria} -
                        ${compra.description ? compra.description : ''} -
                        R$ ${parseFloat(compra.valor).toFixed(2).replace('.', ',')}
                    </li>`;
                $('#lista-ultimas-compras').append(itemLista);
            });

            // Gráfico entradas saidas data atual (hoje)
            const entradasSaidasHojeCanvas = document.getElementById('entradasSaidasHojeChart').getContext('2d');
            new Chart(entradasSaidasHojeCanvas, {
                type: 'bar', // Ou o tipo de gráfico que você preferir
                data: {
                    labels: ['Entradas', 'Saídas'],
                    datasets: [{
                        label: 'Valor (R$)',
                        data: [chartData.entradasSaidasHoje.entradas, chartData.entradasSaidasHoje.saidas],
                        backgroundColor: ['#36a2eb', '#ff6384'] // Cores para entradas e saídas
                    }]
                }
            });

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
                },
                options: { // Opções adicionais para melhor visualização
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
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


            function atualizarUltimasCompras() {
                $.ajax({
                    url: "{{ route('ultimas-compras') }}",
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('#lista-ultimas-compras').empty();

                        data.forEach(function(compra) {

                            // Formatar a data:
                            let dataFormatada = new Date(compra.created_at).toLocaleDateString();

                            // Interpolação correta com template literals:
                            let itemLista = `<li>
                            ${compra.created_at} -
                            ${compra.tipo_despesa} -
                            ${compra.categoria} -
                            ${compra.description ? compra.description : ''} -
                            R$ ${compra.valor.toFixed(2).replace('.', ',')}
                        </li>`;
                            $('#lista-ultimas-compras').append(itemLista);
                        });
                    }
                });
            }

            // Chame a função inicialmente e a cada 30 segundos
            atualizarUltimasCompras();
            setInterval(atualizarUltimasCompras, 30000);



            // Preencher a lista de compras do mês atual até o momento:
            chartData.comprasMesAtualAteMomento.forEach(function(compraDia) {
                let itemLista = `<li class="mb-4">
                                <h3 class="text-lg font-semibold">${compraDia.dia}</h3>
                                <ul>`;
                compraDia.compras.forEach(function(compra) {
                    itemLista += `<li>
                                ${new Date(compra.created_at).getDate()}/${new Date(compra.created_at).getMonth() + 1}/${new Date(compra.created_at).getFullYear()} -
                                ${compra.tipo_despesa} -
                                ${compra.categoria} -
                                ${compra.description ?? ''} -
                                R$ ${parseFloat(compra.valor).toFixed(2).replace('.', ',')}
                            </li>`;
                });
                itemLista += `</ul></li>`;
                $('#lista-compras-mes-atual').append(itemLista);
            });
        </script>

</body>

</html>
