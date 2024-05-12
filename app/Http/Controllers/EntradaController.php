<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entrada; // Certifique-se de importar o modelo correspondente

class EntradaController extends Controller
{
    public function create()
    {
        return view('entradas.form');
    }

    public function store(Request $request)
    {
        // Valide os dados recebidos do formulário
        $request->validate([
            'tipo' => 'required',
            'valor' => 'required|numeric',
            'subtipo' => 'required',
        ]);

        // Crie uma nova entrada no banco de dados
        Entrada::create([
            'user_id' => auth()->id(),
            'tipo' => $request->tipo,
            'subtipo' => $request->subtipo,
            'descricao' => $request->descricao,
            'valor' => $request->valor,
        ]);

        // Redirecione de volta para a página anterior com uma mensagem de sucesso
        return redirect()->back()->with('success', 'Entrada registrada com sucesso.');
    }
}
