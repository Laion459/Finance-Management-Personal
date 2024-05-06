<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;

class ExpenseController extends Controller
{
    // Método para exibir o formulário de registro de despesas
    public function create()
    {
        // Aqui você pode retornar a view do formulário de registro de despesas
        return view('expense-form');
    }

    // Método para lidar com o envio do formulário de registro de despesas
    public function store(Request $request)
    {
        // Validação dos dados do formulário
        $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'payment_method' => 'required',
            // Adicione outras regras de validação conforme necessário
        ]);

        // Criação da nova despesa
        Expense::create([
            'user_id' => auth()->id(),
            'date' => $request->date,
            'amount' => $request->amount,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'payment_method' => $request->payment_method,
        ]);

        // Redirecionamento após o registro da despesa
        return redirect()->route('expenses.create')->with('success', 'Despesa registrada com sucesso!');
    }
}
