<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entrada;
use App\Models\Saida;
use App\Models\Expense;
use Carbon\Carbon;
use App\Models\Category;

class ReportController extends Controller
{
    public function showBudgetTracking()
    {
        $userId = auth()->id();
        $lastMonth = Carbon::now()->subMonth();
        $startDate = $lastMonth->copy()->startOfMonth();
        $endDate = $lastMonth->copy()->endOfMonth();
        $entradasUltimoMes = Entrada::where('user_id', $userId)->whereBetween('date', [$startDate, $endDate])->get();
        $totalEntradasUltimoMes = $entradasUltimoMes->sum('amount');
        $despesasUltimoMes = $this->getExpensesForPeriod($startDate, $endDate, $userId);
        $totalDespesasUltimoMes = $despesasUltimoMes->sum('valor');
        $startDate = Carbon::now()->subDays(7);
        $endDate = Carbon::now();
        $ultimasCompras = $this->getUltimasCompras();
        $totalDespesasMesAtual = $this->getTotalDespesasMesAtual($userId);
        $relatorioMensal = $this->calcularRelatorioMensal($userId);
        $chartData = $this->getChartDataForCurrentMonth($userId);
        $chartData['relatorioMensal'] = $this->calcularRelatorioMensal($userId);
        $ultimasCompras = $this->getUltimasCompras();
        $chartData['listaUltimasCompras'] = $ultimasCompras;
        $chartData['entradasSaidasHoje'] = $this->getEntradasSaidasHoje($userId);
        $comprasUltimoMesPorDia = $this->getComprasMesAtualAteMomento($userId);
        $chartData['comprasUltimoMesPorDia'] = $comprasUltimoMesPorDia;
        $comprasMesAtualJs = [];
        foreach ($comprasUltimoMesPorDia as $compraDia) {
            $comprasDiaJs = [];
            foreach ($compraDia['compras'] as $compra) {
                $comprasDiaJs[] = [
                    'dia' => $compra->created_at->format('d/m/Y'),
                    'tipo_despesa' => $compra->tipo_despesa,
                    'categoria' => $this->replaceIdWithCategoryName($compra->categoria),
                    'description' => $compra->description ?? '',
                    'valor' => $compra->valor
                ];
            }
            $comprasMesAtualJs[] = [
                'dia' => $compraDia['dia'],
                'compras' => $comprasDiaJs
            ];
        }
        return view('budget-tracking', [
            'comprasUltimoMesPorDia' => $comprasUltimoMesPorDia,
            'ultimasCompras' => $ultimasCompras,
            'chartData' => json_encode($chartData),
            'totalDespesasMes' => $totalDespesasMesAtual,
            'replaceIdWithCategoryName' => [$this, 'replaceIdWithCategoryName'],
            'comprasMesAtualJs' => json_encode($comprasMesAtualJs)
        ]);
    }

    public function showMonthlyReports()
    {
        $userId = auth()->id();
        $chartData = $this->getChartDataForCurrentMonth($userId);
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
        $totaisEntradasAnuaisPorCategoria = $this->calcularTotaisAnuaisPorCategoria(Entrada::class, 'type', 'subtype', $userId);
        $chartData['yearlyIncomesByCategory'] = $totaisEntradasAnuaisPorCategoria;
        $totaisSaidasAnuaisPorCategoria = $this->calcularTotaisAnuaisPorCategoria(Expense::class, 'payment_method', 'category_id', $userId, true);
        $chartData['yearlyExpensesByCategory'] = $totaisSaidasAnuaisPorCategoria;
        return view('monthly-reports', [
            'chartData' => json_encode($chartData)
        ]);
    }

    private function getExpensesForPeriod(Carbon $startDate, Carbon $endDate, $userId, $category = null)
    {
        $expensesQuery = Expense::select(
            'amount as valor',
            'date as created_at',
            'category_id as categoria',
            'description',
            'payment_method as tipo_despesa',
            'user_id'
        )
            ->where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate]);
        $saidasQuery = Saida::select(
            'amount as valor',
            'date as created_at',
            'category_id as categoria',
            'description',
            'payment_method as tipo_despesa',
            'user_id'
        )
            ->where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate]);
        if ($category) {
            $expensesQuery->where('category_id', $category);
            $saidasQuery->where('categoria', $category);
        }
        $allExpenses = $expensesQuery->union($saidasQuery)->get();
        return $allExpenses;
    }

    private function calcularTotaisPorCategoria($expenses)
    {
        $totaisPorCategoria = [];
        foreach ($expenses->groupBy('categoria') as $categoria => $itens) {
            $totaisPorCategoria[$categoria] = $itens->sum('valor') ?? 0;
        }
        return $totaisPorCategoria;
    }

    private function calcularTotaisDiarios($expenses)
    {
        $saidasDiarias = [];
        foreach ($expenses->groupBy(function ($item) {
            return $item->created_at ? $item->created_at->format('d') : $item->date->format('d');
        }) as $dia => $itens) {
            $saidasDiarias[(string)$dia] = $itens->sum('valor') ?? 0;
        }
        return $saidasDiarias;
    }

    private function getTotalDespesasMesAtual($userId)
    {
        $primeiroDiaMesAtual = Carbon::now()->startOfMonth();
        $dataAtual = Carbon::now();
        $despesasMesAtual = $this->getExpensesForPeriod($primeiroDiaMesAtual, $dataAtual, $userId);
        $totalDespesasMesAtual = $despesasMesAtual->sum('valor') ?? 0;
        return $totalDespesasMesAtual;
    }

    private function calcularTotaisPorAno($userId)
    {
        $totaisPorAno = [];
        $entradas = Entrada::select('amount as valor', 'date as data')->where('user_id', $userId)->get();
        $despesas = Expense::select('amount as valor', 'date as data')->where('user_id', $userId)->union(Saida::select('amount as valor', 'date as data')->where('user_id', $userId))->get();
        foreach ($entradas->groupBy(function ($item) {
            return Carbon::parse($item->data)->format('Y');
        }) as $ano => $itens) {
            $totaisPorAno[$ano]['entradas'] = $itens->sum('valor') ?? 0;
        }
        foreach ($despesas->groupBy(function ($item) {
            return Carbon::parse($item->data)->format('Y');
        }) as $ano => $itens) {
            $totaisPorAno[$ano]['saidas'] = $itens->sum('valor') ?? 0;
        }
        return $totaisPorAno;
    }

    private function calcularTotaisAnuaisPorCategoria($model, $tipoField, $subtipoField, $userId, $isExpense = false)
    {
        $totaisPorAnoCategoria = [];
        $itens = $model::where('user_id', $userId)->get();
        foreach ($itens->groupBy(function ($item) use ($isExpense) {
            if ($isExpense && $item->date) {
                return Carbon::parse($item->date)->format('Y');
            } elseif ($item->date) {
                return Carbon::parse($item->date)->format('Y');
            } else {
                return 'Sem data';
            }
        }) as $ano => $itensPorAno) {
            $totaisPorAnoCategoria[$ano] = [];
            foreach ($itensPorAno->groupBy($tipoField) as $tipo => $itensPorTipo) {
                foreach ($itensPorTipo->groupBy($subtipoField) as $subtipo => $itens) {
                    $categoria = $tipo . ' - ' . $subtipo;
                    $totaisPorAnoCategoria[$ano][$categoria] = $itens->sum('amount') ?? 0;
                }
            }
        }
        return $totaisPorAnoCategoria;
    }

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
            $entradas = Entrada::where('user_id', $userId)->whereBetween('date', [$startDate, $endDate])->sum('amount');
            $despesas = $this->getExpensesForPeriod($startDate, $endDate, $userId)->sum('valor');
            $relatorioMensal['labels'][] = $data->format('d/m');
            $relatorioMensal['entradas'][] = $entradas ?? 0;
            $relatorioMensal['saidas'][] = $despesas ?? 0;
        }
        return $relatorioMensal;
    }

    private function getChartDataForCurrentMonth($userId)
    {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now();
        $entradas = Entrada::where('user_id', $userId)->whereBetween('date', [$startDate, $endDate])->get();
        $despesas = $this->getExpensesForPeriod($startDate, $endDate, $userId);
        $totalEntradas = $entradas->sum('amount');
        $totalSaidas = $despesas->sum('valor');
        $entradasPorCategoria = $this->calcularTotaisPorCategoria($entradas);
        $saidasPorCategoria = $this->calcularTotaisPorCategoria($despesas);
        $saidasDiarias = $this->calcularTotaisDiarios($despesas);
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
        $ultimasCompras = $this->getExpensesForPeriod(Carbon::now()->subDays(12), Carbon::now(), $userId)->sortByDesc('created_at')->take(12);
        return $ultimasCompras->values();
    }

    public function getEntradasSaidasHoje($userId)
    {
        $hoje = Carbon::now()->startOfDay();
        $entradas = Entrada::where('user_id', $userId)->whereDate('date', $hoje)->sum('amount');
        $saidas = $this->getExpensesForPeriod($hoje, Carbon::now(), $userId)->sum('valor');
        return [
            'entradas' => $entradas ?? 0,
            'saidas' => $saidas ?? 0
        ];
    }

    public function getComprasMesAtualAteMomento($userId)
    {
        $primeiroDiaMesAtual = Carbon::now()->startOfMonth();
        $dataAtual = Carbon::now();
        $comprasMesAtual = $this->getExpensesForPeriod($primeiroDiaMesAtual, $dataAtual, $userId);
        $comprasPorDia = [];
        foreach ($comprasMesAtual->groupBy(function ($item) {
            return $item->created_at->format('Y-m-d');
        }) as $dia => $compras) {
            $comprasPorDia[] = [
                'dia' => Carbon::parse($dia)->format('d/m/Y'),
                'compras' => $compras
            ];
        }
        $comprasPorDia = array_reverse($comprasPorDia);
        return $comprasPorDia;
    }

    public function replaceIdWithCategoryName($id)
    {
        $categoriaMap = [
            1 => 'PIX - PIX',
            2 => 'Crédito - Crédito',
            3 => 'Débito - Débito',
            4 => 'Dinheiro - Dinheiro',
            5 => 'Outro - Outro',
            6 => 'Salário - Salário Mensal',
            7 => 'Salário - Bônus',
            8 => 'Salário - Participação nos Lucros',
            9 => 'Salário - Horas Extras',
            10 => 'Investimentos - Dividendos',
            11 => 'Investimentos - Juros',
            12 => 'Investimentos - Ganhos de Capital',
            13 => 'Renda Extra - Trabalho Freelance',
            14 => 'Renda Extra - Vendas Online',
            15 => 'Renda Extra - Aluguel de Imóveis',
            16 => 'Presentes - Dinheiro',
            17 => 'Presentes - Bens de Valor',
            18 => 'Outros - Restituição de Imposto',
            19 => 'Outros - Prêmios',
            20 => 'Outros - Heranças',
            21 => 'Moradia - Moradia',
            22 => 'Transporte - Transporte',
            23 => 'Alimentação - Alimentação',
            24 => 'Educação - Educação',
            25 => 'Saúde - Saúde',
            26 => 'Lazer - Lazer',
            27 => 'Outros - Outros'
        ];
        return $categoriaMap[$id] ?? 'Categoria Desconhecida';
    }
}
