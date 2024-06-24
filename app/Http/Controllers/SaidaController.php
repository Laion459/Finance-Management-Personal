<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Saida;
use App\Models\Category;
use App\Events\NewNotification;
use App\Models\Notification;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SaidaController extends Controller
{
    public function create()
    {
        // Consulta para obter as categorias de tipos de despesas
        $expenseCategories = Category::where('category_type', 'expense')->pluck('subtype', 'id');

        // Consulta para obter as categorias de métodos de pagamento
        $paymentCategories = Category::where('category_type', 'payment_method')->pluck('subtype', 'id');

        // Converta os arrays associativos para arrays de objetos
        $expenseCategories = $expenseCategories->map(function ($subtype, $id) {
            return ['id' => $id, 'name' => $subtype];
        })->toArray();

        $paymentCategories = $paymentCategories->map(function ($subtype, $id) {
            return ['id' => $id, 'name' => $subtype];
        })->toArray();

        // Passe as categorias para a view
        return view('saidas.form', compact('expenseCategories', 'paymentCategories'));
    }

    public function store(Request $request)
    {
        // Validação dos dados do formulário
        $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'payment_method' => 'required',
            'description' => 'nullable',
        ]);

        try {
            // Tenta encontrar a categoria pelo ID fornecido
            $category = Category::findOrFail($request->category_id);
        } catch (ModelNotFoundException $e) {
            // Se não encontrar a categoria, retorna com erro
            return back()->withErrors(['category_id' => 'The selected category id is invalid.']);
        }

        // Recupera o ID do usuário autenticado
        $userId = auth()->id();

        // Criação da nova despesa
        $saida = Saida::create([
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
        $notification->message = 'N: ' . $saida->category_id . ' - R$ ' . number_format($saida->amount, 2);
        $notification->save();

        // Emitir evento de nova notificação via broadcast
        broadcast(new NewNotification($notification));

        // Redirecionamento após o registro da despesa
        return redirect()->route('saidas.form')->with('success', 'Saída registrada com sucesso!');
    }
}
