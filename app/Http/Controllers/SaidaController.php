<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Saida;

class SaidaController extends Controller
{
    public function create()
    {
        return view('saidas.form');
    }

    public function store(Request $request)
    {
        // Valide os dados recebidos do formulário
        $request->validate([
            'tipo_despesa' => 'required',
            'categoria' => 'required',
            'descricao' => 'nullable|string',
            'valor' => 'required|numeric',
        ]);

        // Verifique se a categoria recebida está na lista de categorias permitidas
        $categoriasPermitidas = ['Aluguel', 'Condomínio', 'IPTU', 'Água', 'Luz', 'Gás', 'Internet', 'Telefone', 'TV a cabo', 'Manutenção', 'Outros']; // Defina a lista de categorias permitidas
        if (!in_array($request->categoria, $categoriasPermitidas)) {
            return redirect()->back()->with('error', 'Categoria inválida.'); // Redirecione de volta com uma mensagem de erro se a categoria não for permitida
        }

        // Crie uma nova saída no banco de dados
        Saida::create([
            'user_id' => auth()->id(),
            'tipo' => $request->tipo_despesa,
            'descricao' => $request->descricao,
            'valor' => $request->valor,
            'categoria' => $request->categoria,
        ]);

        // Redirecione de volta para a página anterior com uma mensagem de sucesso
        return redirect()->back()->with('success', 'Saída registrada com sucesso.');
    }
}
