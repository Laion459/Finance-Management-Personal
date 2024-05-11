<?php

namespace App\Http\Controllers;

use App\Models\Notification;
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
        ]);

        // Recupera o ID do usuário autenticado
        $userId = auth()->id();

        // Criação da nova despesa
        $expense = Expense::create([
            'user_id' => $userId,
            'date' => $request->date,
            'amount' => $request->amount,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'payment_method' => $request->payment_method,
        ]);



        // Enviar uma notificação para o usuário
        $notification = new Notification();
        $notification->user_id = auth()->id();
        $notification->message = 'Nova despesa cadastrada.';
        $notification->save();

        // Redirecionamento após o registro da despesa
        return redirect()->route('expenses.form')->with('success', 'Despesa registrada com sucesso!');
    }
}
