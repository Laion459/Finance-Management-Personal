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
        /*
        // Verifique se a categoria recebida está na lista de categorias permitidas
        $categoriasPermitidas = ['Aluguel', 'Condomínio', 'IPTU', 'Água', 'Luz', 'Gás', 'Internet', 'Telefone', 'TV a cabo', 'Manutenção', 'Outros'];
        $categoriaPermitida = in_array($request->categoria, $categoriasPermitidas);

        // Se a categoria recebida não estiver na lista de categorias permitidas, retorne uma mensagem de erro
        if (!$categoriaPermitida) {
            return redirect()->back()->with('error', 'Categoria inválida.');
        }
*/
        // Definir um valor padrão para tipo_despesa, se não estiver presente no request
        $tipoDespesa = $request->tipo_despesa ?? 'outros';
        // Crie uma nova saída no banco de dados
        Saida::create([
            'user_id' => auth()->id(),
            'tipo_despesa' => $request->tipo_despesa,
            'descricao' => $request->descricao,
            'valor' => $request->valor,
            'categoria' => $request->categoria,
        ]);

        // Redirecione de volta para a página anterior com uma mensagem de sucesso
        return redirect()->back()->with('success', 'Saída registrada com sucesso.');
    }
}
