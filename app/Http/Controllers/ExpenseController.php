<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use Illuminate\Support\Facades\Validator;


class ExpenseController extends Controller
{
    /**
     * Exibe o formulário de registro de despesas.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('expense-form');
    }

    /**
     * Armazena uma nova despesa.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'category' => 'required|exists:categories,id',
            // Adicione mais regras de validação conforme necessário
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Crie uma nova despesa com os dados fornecidos
        Expense::create($request->all());

        return redirect()->route('expenses.create')->with('success', 'Despesa registrada com sucesso!');
    }
}
