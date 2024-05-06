<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Saida; // Certifique-se de importar o modelo correspondente

class SaidaController extends Controller
{
    public function create()
    {
        return view('saidas');
    }

    public function store(Request $request)
    {
        // Valide os dados recebidos do formulário
        $request->validate([
            'tipo' => 'required',
            'valor' => 'required|numeric',
        ]);

        // Crie uma nova saída no banco de dados
        Saida::create([
            'user_id' => auth()->id(),
            'tipo' => $request->tipo,
            'descricao' => $request->descricao,
            'valor' => $request->valor,
        ]);

        // Redirecione de volta para a página anterior com uma mensagem de sucesso
        return redirect()->back()->with('success', 'Saída registrada com sucesso.');
    }
}
