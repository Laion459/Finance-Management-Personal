<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Exibe os relatórios mensais.
     *
     * @return \Illuminate\View\View
     */
    public function showMonthlyReports()
    {
        // Aqui você pode adicionar lógica para buscar e formatar os dados para os relatórios mensais
        // Por enquanto, retornaremos apenas a view
        return view('monthly-reports');
    }
}
