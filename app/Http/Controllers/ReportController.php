<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use Carbon\Carbon;

class ReportController extends Controller
{
    // Método para a visualização de relatórios mensais
    public function showMonthlyReports()
    {
        // Recupera o mês e o ano atual
        $month = Carbon::now()->format('m');
        $year = Carbon::now()->format('Y');

        // Recupera as despesas do mês atual
        $expenses = Expense::whereMonth('date', $month)
                            ->whereYear('date', $year)
                            ->where('user_id', auth()->id()) // Filtra apenas as despesas do usuário logado
                            ->get();

        // Agrupa as despesas por categoria
        $expensesByCategory = $expenses->groupBy('category_id');

        // Formata os dados para o formato esperado pelo Chart.js
        $chartData = [];
        foreach ($expensesByCategory as $categoryId => $expenses) {
            $categoryName = $expenses->first()->category->name;
            $totalAmount = $expenses->sum('amount');
            $chartData[] = [
                'label' => $categoryName,
                'amount' => $totalAmount
            ];
        }

        // Retorna a view 'monthly-reports' com os dados das despesas do mês atual e o total
        return view('monthly-reports', [
            'chartData' => $chartData
        ]);
    }
}
