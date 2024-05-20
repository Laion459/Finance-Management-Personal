<x-app-layout>
    <x-slot name="header">
        <h1 class="text-white text-2xl mb-4 flex justify-center items-center">Relatórios Mensais</h1>
    </x-slot>

    <div class="max-w-7xl mx-auto text-white text-2xl mb-4">
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script>
        // Obter os dados JSON da view
        const chartData = JSON.parse('{!! $chartData !!}');

        // Dicionário com os nomes das categorias
        const nameCategory = {
            1: 'PIX - PIX',
            2: 'Crédito - Crédito',
            3: 'Débito - Débito',
            4: 'Dinheiro - Dinheiro',
            5: 'Outro - Outro',
            6: 'Salário - Salário Mensal',
            7: 'Salário - Bônus',
            8: 'Salário - Participação nos Lucros',
            9: 'Salário - Horas Extras',
            10: 'Investimentos - Dividendos',
            11: 'Investimentos - Juros',
            12: 'Investimentos - Ganhos de Capital',
            13: 'Renda Extra - Trabalho Freelance',
            14: 'Renda Extra - Vendas Online',
            15: 'Renda Extra - Aluguel de Imóveis',
            16: 'Presentes - Dinheiro',
            17: 'Presentes - Bens de Valor',
            18: 'Outros - Restituição de Imposto',
            19: 'Outros - Prêmios',
            20: 'Outros - Heranças',
            21: 'Moradia - Moradia',
            22: 'Transporte - Transporte',
            23: 'Alimentação - Alimentação',
            24: 'Educação - Educação',
            25: 'Saúde - Saúde',
            26: 'Lazer - Lazer',
            27: 'Outros - Outros'
        };

        // Função para substituir IDs por nomes de categoria
        function replaceIdWithCategoryName(id) {
            return nameCategory[id] || id; // Retorna o nome da categoria ou o ID se o nome não for encontrado
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
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Total de Entradas e Saídas',
                        position: 'top'
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
                        color: '#fff'
                    }
                }
            }
        });

        // Gráfico de Despesas por Categoria
        const expensesByCategoryChartCanvas = document.getElementById('expensesByCategoryChart').getContext('2d');
        new Chart(expensesByCategoryChartCanvas, {
            type: 'bar',
            data: {
                labels: chartData.expensesByCategory.labels.map(replaceIdWithCategoryName),
                datasets: [{
                    label: 'Despesas por Categoria (R$)',
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
                        text: 'Distribuição de Saídas'
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'end',
                        color: '#fff',
                        font: {
                            weight: 'bold'
                        }
                    },
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            display: false
                        }
                    },
                    y: {
                        title: {
                            display: false
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
                labels: chartData.incomesByCategory.labels.map(replaceIdWithCategoryName),
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
                        generateLabels: (chart) => {
                            const dataset = chart.data.datasets[0];
                            return dataset.data.map((value, index) => {
                                const percentage = ((value / dataset.data.reduce((a, b) => a + b, 0)) * 100).toFixed(2);
                                return {
                                    text: `${chart.data.labels[index]} (${percentage}%)`,
                                    fillStyle: dataset.backgroundColor[index],
                                    hidden: !chart.getDatasetMeta(0).data[index].hidden
                                };
                            });
                        }
                    },
                    legend: {
                        display: true,
                        position: 'bottom'
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
                        text: 'Total de Entradas e Saídas Anual',
                        position: 'top'
                    }
                }
            }
        });

        // Gráfico de Total de Entradas Anual por Categoria
        const yearlyIncomesByCategoryChartCanvas = document.getElementById('yearlyIncomesByCategoryChart').getContext('2d');
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
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'end',
                        color: '#fff',
                        font: {
                            weight: 'bold'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Adicionar datasets para cada categoria
        for (const categoria in chartData.yearlyIncomesByCategory[Object.keys(chartData.yearlyIncomesByCategory)[0]]) {
            yearlyIncomesByCategoryChart.data.datasets.push({
                label: replaceIdWithCategoryName(categoria),
                data: Object.values(chartData.yearlyIncomesByCategory).map(ano => ano[categoria] || 0),
                backgroundColor: getRandomColor() // Função para gerar cores aleatórias
            });
        }
        yearlyIncomesByCategoryChart.update();

        // Gráfico de Total de Saídas Anual por Categoria
        const yearlyExpensesByCategoryChartCanvas = document.getElementById('yearlyExpensesByCategoryChart').getContext('2d');
        const yearlyExpensesByCategoryChart = new Chart(yearlyExpensesByCategoryChartCanvas, {
            type: 'bar',
            data: {
                labels: Object.keys(chartData.yearlyExpensesByCategory), // Anos
                datasets: []
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Total de Saídas Anual por Categoria'
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'end',
                        color: '#fff',
                        font: {
                            weight: 'bold'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Adicionar datasets para cada categoria
        for (const categoria in chartData.yearlyExpensesByCategory[Object.keys(chartData.yearlyExpensesByCategory)[0]]) {
            yearlyExpensesByCategoryChart.data.datasets.push({
                label: replaceIdWithCategoryName(categoria),
                data: Object.values(chartData.yearlyExpensesByCategory).map(ano => ano[categoria] || 0),
                backgroundColor: getRandomColor()
            });
        }
        yearlyExpensesByCategoryChart.update();

        // Função para gerar cores aleatórias
        function getRandomColor() {
            const letters = '0123456789ABCDEF';
            let color = '#';
            for (let i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }
    </script>
</x-app-layout>
