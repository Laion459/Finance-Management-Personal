<x-app-layout>
    <x-slot name="header">
        <h1 class="text-white text-2xl mb-4 flex justify-center items-center">Controle de Orçamento</h1>
    </x-slot>

    <hr>
    <br>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-white text-2xl mb-4 ">
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
            <br>
            <hr><br>
            <div class="bg-gray-800 rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Gráfico de Entradas e Saídas de Hoje</h2>
                <canvas id="entradasSaidasHojeChart"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6">
            <div class="bg-gray-800 rounded-lg p-6">
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

        <div class="grid grid-cols-1 gap-6">
            <div class="bg-gray-800 rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Compras do Mês Atual até o Momento</h2>
                <ul id="lista-compras-mes-atual">
                </ul>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script>
        const chartData = JSON.parse('{!! $chartData !!}');
        const comprasMesAtualJs = JSON.parse('{!! $comprasMesAtualJs !!}');
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
                        text: 'Total de Entradas e Saídas',
                        position: 'bottom'
                    }
                }
            }
        });


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
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        const entradasSaidasHojeCanvas = document.getElementById('entradasSaidasHojeChart').getContext('2d');
        new Chart(entradasSaidasHojeCanvas, {
            type: 'bar',
            data: {
                labels: ['Entradas', 'Saídas'],
                datasets: [{
                    label: 'Valor (R$)',
                    data: [chartData.entradasSaidasHoje.entradas, chartData.entradasSaidasHoje.saidas],
                    backgroundColor: ['#36a2eb', '#ff6384']
                }]
            }
        });
        comprasMesAtualJs.forEach(function(compraDia) {
            let itemLista = `<li class="mb-4">
                        <h3 class="text-lg font-semibold">${compraDia.dia}</h3>
                        <ul>`;
            compraDia.compras.forEach(function(compra) {
                itemLista += `<li>
                    ${new Date(compra.dia).getDate()}/${new Date(compra.dia).getMonth() + 1}/${new Date(compra.dia).getFullYear()} -
                    ${compra.tipo_despesa} -
                    ${compra.categoria} -
                    ${compra.description ?? ''} -
                    R$ ${parseFloat(compra.valor).toFixed(2).replace('.', ',')}
                </li>`;
            });
            itemLista += `</ul></li>`;
            $('#lista-compras-mes-atual').append(itemLista);
        });

        // Renderizando a lista de compras do mês atual até o momento
        comprasMesAtualJs.forEach(function(compraDia) {
            let itemLista = `<li class="mb-4">
                    <h3 class="text-lg font-semibold">${compraDia.dia}</h3>
                    <ul>`;
            compraDia.compras.forEach(function(compra) {
                // Convertendo a string para o formato yyyy-mm-dd
                let [dia, mes, ano] = compra.dia.split('/');
                let dataCompra = new Date(`${ano}-${mes.padStart(2, '0')}-${dia.padStart(2, '0')}`);

                // Extraindo as partes da data
                let diaFormatado = dataCompra.getDate();
                let mesFormatado = dataCompra.getMonth() + 1; // Meses começam em 0
                let anoFormatado = dataCompra.getFullYear();

                itemLista += `<li>
                    ${diaFormatado}/${mesFormatado}/${anoFormatado} -
                    ${compra.tipo_despesa} -
                    ${compra.categoria} -
                    ${compra.description ?? ''} -
                    R$ ${parseFloat(compra.valor).toFixed(2).replace('.', ',')}
                </li>`;
            });
            itemLista += `</ul></li>`;
            $('#lista-compras-mes-atual').append(itemLista);
        });

        // Renderizando a lista de últimas compras
        chartData.listaUltimasCompras.forEach(function(compra) {
            // Formatando as datas corretamente
            let dataCompra = new Date(compra.created_at);
            let dia = dataCompra.getDate();
            let mes = dataCompra.getMonth() + 1; // Meses começam em 0
            let ano = dataCompra.getFullYear();

            let itemLista = `<li>
                    ${dia}/${mes}/${ano} -
                    ${compra.tipo_despesa} -
                    ${compra.categoria} -
                    ${compra.description ?? ''} -
                    R$ ${parseFloat(compra.valor).toFixed(2).replace('.', ',')}
                </li>`;
            $('#lista-ultimas-compras').append(itemLista);
        });
    </script>
</x-app-layout>
