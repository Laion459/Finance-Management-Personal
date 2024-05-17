<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entrada;
use App\Models\Saida;
use App\Models\Expense;
use Carbon\Carbon;

class ReportController extends Controller
{
    // Método para exibir o acompanhamento do orçamento
    public function showBudgetTracking()
    {
        // Obter o ID do usuário autenticado
        $userId = auth()->id();

        // Período do último mês
        $lastMonth = Carbon::now()->subMonth();
        $startDate = $lastMonth->copy()->startOfMonth();
        $endDate = $lastMonth->copy()->endOfMonth();

        // Obter todas as receitas (entradas) do último mês
        $entradasUltimoMes = Entrada::where('user_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        // Calcular o total das receitas do último mês
        $totalEntradasUltimoMes = $entradasUltimoMes->sum('valor');

        // Obter todas as despesas do último mês (de ambas as tabelas)
        $despesasUltimoMes = $this->getExpensesForPeriod($startDate, $endDate, $userId);

        // Calcular o total das despesas do último mês
        $totalDespesasUltimoMes = $despesasUltimoMes->sum('valor');

        // Obter as últimas 7 despesas (de ambas as tabelas)
        $startDate = Carbon::now()->subDays(7);
        $endDate = Carbon::now();
        $ultimasCompras = $this->getUltimasCompras(); // Busca as últimas 12 compras

        // Calcular o total de despesas do mês atual
        $totalDespesasMesAtual = $this->getTotalDespesasMesAtual($userId);

        // Inicializar o array para o relatório mensal (últimos 7 dias)
        $relatorioMensal = $this->calcularRelatorioMensal($userId);

        // Obter dados para gráficos do mês atual
        $chartData = $this->getChartDataForCurrentMonth($userId);

        // Adicionar relatorioMensal a $chartData:
        $chartData['relatorioMensal'] = $this->calcularRelatorioMensal($userId);

        // Obter as últimas 12 compras
        $ultimasCompras = $this->getUltimasCompras();

        // Adicionar lista-ultimas-compras ao $chartData
        $chartData['listaUltimasCompras'] = $ultimasCompras;

        // Adicionar entradas e saidas do dia atual (hoje)
        $chartData['entradasSaidasHoje'] = $this->getEntradasSaidasHoje($userId);

        // Obter as compras do último mês por dia
        $comprasUltimoMesPorDia = $this->getComprasMesAtualAteMomento($userId);

        // Adicionar as compras do último mês por dia ao $chartData
        $chartData['comprasUltimoMesPorDia'] = $comprasUltimoMesPorDia;


        //dd($chartData);

        // Retornar a visualização com os dados
        return view('budget-tracking', [
            'comprasUltimoMesPorDia' => $comprasUltimoMesPorDia,
            'ultimasCompras' => $ultimasCompras,
            'chartData' => json_encode($chartData), // Converta para JSON aqui
            'totalDespesasMes' => $totalDespesasMesAtual, // Total de despesas do mês atual
        ]);
    }

    // Método para exibir os relatórios mensais
    public function showMonthlyReports()
    {
        $userId = auth()->id();

        // Obter dados para gráficos do mês atual
        $chartData = $this->getChartDataForCurrentMonth($userId);

        // Calcular totais de entrada e saídas por ano
        $totaisPorAno = $this->calcularTotaisPorAno($userId);
        $chartData['yearlyTotals'] = [
            'labels' => array_keys($totaisPorAno),
            'entradas' => array_map(function ($item) {
                return $item['entradas'];
            }, array_values($totaisPorAno)),
            'saidas' => array_map(function ($item) {
                return $item['saidas'];
            }, array_values($totaisPorAno))
        ];

        // Calcular total anual de entradas por categoria
        $totaisEntradasAnuaisPorCategoria = $this->calcularTotaisAnuaisPorCategoria(Entrada::class, 'tipo', 'subtipo', $userId);
        $chartData['yearlyIncomesByCategory'] = $totaisEntradasAnuaisPorCategoria;

        // Calcular total anual de saídas por categoria
        $totaisSaidasAnuaisPorCategoria = $this->calcularTotaisAnuaisPorCategoria(Expense::class, 'payment_method', 'category_id', $userId, true);
        $chartData['yearlyExpensesByCategory'] = $totaisSaidasAnuaisPorCategoria;

        // Retornar a visualização com os dados
        return view('monthly-reports', [
            'chartData' => json_encode($chartData)
        ]);
    }

    // Função unificada para obter despesas de 'expenses' e 'saidas'
    private function getExpensesForPeriod(Carbon $startDate, Carbon $endDate, $userId, $category = null)
    {
        $expensesQuery = Expense::select(
            'amount as valor', // Alias para unificar com 'saidas'
            'date as created_at', // Alias para unificar com 'saidas'
            'category_id as categoria',  // Alias para unificar com 'saidas'
            'description',
            'payment_method as tipo_despesa', // Alias para unificar com 'saidas'
            'user_id'
        )
            ->where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate]);

        $saidasQuery = Saida::select(
            'valor',
            'created_at',
            'categoria',
            'descricao as description', // Alias para unificar com 'expenses'
            'tipo_despesa',
            'user_id'
        )
            ->where('user_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($category) {
            $expensesQuery->where('category_id', $category);
            $saidasQuery->where('categoria', $category);
        }

        // Combine os resultados usando union
        $allExpenses = $expensesQuery->union($saidasQuery)->get();

        return $allExpenses;
    }

    // Método para calcular os totais de despesas por categoria
    private function calcularTotaisPorCategoria($expenses)
    {
        $totaisPorCategoria = [];

        // Agrupa as despesas por categoria
        foreach ($expenses->groupBy('categoria') as $categoria => $itens) {
            // Soma as despesas de cada categoria, tratando valores nulos
            $totaisPorCategoria[$categoria] = $itens->sum('valor') ?? 0;
        }

        return $totaisPorCategoria;
    }


    // Método para calcular os totais diários de despesas
    private function calcularTotaisDiarios($expenses)
    {
        $saidasDiarias = [];

        // Agrupa as despesas pelo dia
        foreach ($expenses->groupBy(function ($item) {
            // Define a chave de agrupamento como o dia da despesa
            // Verifica se a data da despesa está em 'created_at' ou 'date'
            return $item->created_at ? $item->created_at->format('d') : $item->date->format('d');
        }) as $dia => $itens) {
            // Soma as despesas de cada dia, tratando valores nulos
            $saidasDiarias[(string)$dia] = $itens->sum('valor') ?? 0;
        }

        return $saidasDiarias;
    }

    // Método para obter o total de despesas do mês atual
    private function getTotalDespesasMesAtual($userId)
    {
        // Define o período do mês atual
        $primeiroDiaMesAtual = Carbon::now()->startOfMonth();
        $dataAtual = Carbon::now();

        // Obter todas as despesas do mês atual (de ambas as tabelas)
        $despesasMesAtual = $this->getExpensesForPeriod($primeiroDiaMesAtual, $dataAtual, $userId);

        // Soma as despesas do mês atual, tratando valores nulos
        $totalDespesasMesAtual = $despesasMesAtual->sum('valor') ?? 0;

        return $totalDespesasMesAtual;
    }

    // Método para calcular os totais de entrada e saída por ano
    private function calcularTotaisPorAno($userId)
    {
        $totaisPorAno = [];

        $entradas = Entrada::select('valor', 'created_at as data')
            ->where('user_id', $userId)->get();

        $despesas = Expense::select('amount as valor', 'date as data')
            ->where('user_id', $userId)
            ->union(Saida::select('valor', 'created_at as data')
                ->where('user_id', $userId))
            ->get();

        foreach ($entradas->groupBy(function ($item) {
            return Carbon::parse($item->data)->format('Y'); // Converte para Carbon
        }) as $ano => $itens) {
            $totaisPorAno[$ano]['entradas'] = $itens->sum('valor') ?? 0;
        }

        foreach ($despesas->groupBy(function ($item) {
            return Carbon::parse($item->data)->format('Y'); // Converte para Carbon
        }) as $ano => $itens) {
            $totaisPorAno[$ano]['saidas'] = $itens->sum('valor') ?? 0;
        }

        return $totaisPorAno;
    }

    // Método para calcular os totais anuais por categoria (entradas ou saídas)
    private function calcularTotaisAnuaisPorCategoria($model, $tipoField, $subtipoField, $userId, $isExpense = false)
    {
        $totaisPorAnoCategoria = [];

        $itens = $model::where('user_id', $userId)->get();

        foreach ($itens->groupBy(function ($item) use ($isExpense) {
            // Verifica se é despesa e se a data existe
            if ($isExpense && $item->date) {
                return Carbon::parse($item->date)->format('Y');
            } elseif ($item->created_at) {
                return Carbon::parse($item->created_at)->format('Y');
            } else {
                return 'Sem data'; // Ou uma string que faça sentido no seu contexto
            }
        }) as $ano => $itensPorAno) {
            $totaisPorAnoCategoria[$ano] = [];

            foreach ($itensPorAno->groupBy($tipoField) as $tipo => $itensPorTipo) {
                foreach ($itensPorTipo->groupBy($subtipoField) as $subtipo => $itens) {
                    $categoria = $tipo . ' - ' . $subtipo;
                    $totaisPorAnoCategoria[$ano][$categoria] = $itens->sum('valor') ?? 0;
                }
            }
        }

        return $totaisPorAnoCategoria;
    }

    // Método para calcular o relatório mensal (últimos 7 dias)
    private function calcularRelatorioMensal($userId)
    {
        $relatorioMensal = [
            'labels' => [],
            'entradas' => [],
            'saidas' => []
        ];

        for ($i = 6; $i >= 0; $i--) {
            $data = Carbon::now()->subDays($i);
            $startDate = $data->copy()->startOfDay();
            $endDate = $data->copy()->endOfDay();

            $entradas = Entrada::where('user_id', $userId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('valor');

            $despesas = $this->getExpensesForPeriod($startDate, $endDate, $userId)
                ->sum('valor');

            $relatorioMensal['labels'][] = $data->format('d/m');
            $relatorioMensal['entradas'][] = $entradas ?? 0;
            $relatorioMensal['saidas'][] = $despesas ?? 0;
        }

        return $relatorioMensal;
    }


    // Método para obter os dados para os gráficos do mês atual
    private function getChartDataForCurrentMonth($userId)
    {
        // Define o período do mês atual
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now();

        // Obter todas as receitas (entradas) do mês atual
        $entradas = Entrada::where('user_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        // Obter todas as despesas do mês atual (de ambas as tabelas)
        $despesas = $this->getExpensesForPeriod($startDate, $endDate, $userId);

        // Calcula totais de entradas e saídas
        $totalEntradas = $entradas->sum('valor');
        $totalSaidas = $despesas->sum('valor');

        // Calcula totais por categoria
        $entradasPorCategoria = $this->calcularTotaisPorCategoria($entradas);
        $saidasPorCategoria = $this->calcularTotaisPorCategoria($despesas);

        // Calcula totais diários de despesas
        $saidasDiarias = $this->calcularTotaisDiarios($despesas);

        // Cria um array com todos os dados para os gráficos
        return [
            'total' => [
                'labels' => ['Entradas', 'Saídas'],
                'data' => [$totalEntradas, $totalSaidas]
            ],
            'dailyExpenses' => [
                'labels' => array_keys($saidasDiarias),
                'data' => array_values($saidasDiarias)
            ],
            'expensesByCategory' => [
                'labels' => array_keys($saidasPorCategoria),
                'data' => array_values($saidasPorCategoria)
            ],
            'incomesByCategory' => [
                'labels' => array_keys($entradasPorCategoria),
                'data' => array_values($entradasPorCategoria)
            ]
        ];
    }


    public function getUltimasCompras()
    {
        $userId = auth()->id();
        $ultimasCompras = $this->getExpensesForPeriod(Carbon::now()->subDays(12), Carbon::now(), $userId)
            ->sortByDesc('created_at') // Ordena pela data de criação
            ->take(12); // Limita a 12 compras

        // Retorne os dados no formato que você precisa para o JavaScript
        // (por exemplo, um array de objetos)
        return $ultimasCompras->values();
    }


    public function getEntradasSaidasHoje($userId)
    {
        $hoje = Carbon::now()->startOfDay(); // Início do dia de hoje

        $entradas = Entrada::where('user_id', $userId)
            ->whereDate('created_at', $hoje)
            ->sum('valor');

        $saidas = $this->getExpensesForPeriod($hoje, Carbon::now(), $userId) // Use a função unificada
            ->sum('valor');

        return [
            'entradas' => $entradas ?? 0,
            'saidas' => $saidas ?? 0
        ];
    }



    public function getComprasMesAtualAteMomento($userId)
    {
        // Obter o primeiro dia do mês atual
        $primeiroDiaMesAtual = Carbon::now()->startOfMonth();

        // Obter a data atual
        $dataAtual = Carbon::now();

        // Obter todas as compras do mês atual até a data atual
        $comprasMesAtual = $this->getExpensesForPeriod($primeiroDiaMesAtual, $dataAtual, $userId);

        // Inicializar um array para armazenar as compras por dia
        $comprasPorDia = [];

        // Organizar as compras por dia
        foreach ($comprasMesAtual->groupBy(function ($item) {
            return $item->created_at->format('Y-m-d'); // Agrupa por data (dia)
        }) as $dia => $compras) {
            $comprasPorDia[] = [
                'dia' => Carbon::parse($dia)->format('d/m/Y'), // Formatando a data
                'compras' => $compras // Lista de compras para este dia
            ];
        }

        // Ordenar as compras por data, do mais recente para o mais antigo
        $comprasPorDia = array_reverse($comprasPorDia);

        return $comprasPorDia;
    }
}
