<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entrada;
use App\Models\Saida;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function showMonthlyReports()
    {
        $month = Carbon::now()->format('m');
        $year = Carbon::now()->format('Y');
        $userId = auth()->id();

        // Entradas
        $entradas = Entrada::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->where('user_id', $userId)
            ->get();
        $totalEntradas = $entradas->sum('valor');
        $entradasPorCategoria = $this->calcularTotaisPorCategoria($entradas);

        // Saídas
        $saidas = Saida::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->where('user_id', $userId)
            ->get();
        $totalSaidas = $saidas->sum('valor');
        $saidasPorCategoria = $this->calcularTotaisPorCategoria($saidas);

        // Calcular totais de saída diários
        $saidasDiarias = $this->calcularTotaisDiarios($saidas);

        // Criar um array com todos os dados para os gráficos
        $chartData = [
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

        return view('monthly-reports', [
            'chartData' => json_encode($chartData)
        ]);
    }

    private function calcularTotaisPorCategoria($dados)
    {
        $totaisPorCategoria = [];
        foreach ($dados->groupBy('categoria') as $categoria => $itens) {
            $totaisPorCategoria[$categoria] = $itens->sum('valor');
        }
        return $totaisPorCategoria;
    }

    private function calcularTotaisDiarios($saidas)
    {
        $saidasDiarias = [];
        foreach ($saidas->groupBy(function ($item) {
            return $item->created_at->format('d');
        }) as $dia => $itens) {
            // Converter $dia (número) para string
            $saidasDiarias[(string)$dia] = $itens->sum('valor');
        }
        return $saidasDiarias;
    }
}
