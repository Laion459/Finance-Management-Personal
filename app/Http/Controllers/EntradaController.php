<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entrada;
use App\Models\Category;

class EntradaController extends Controller
{
    public function create()
    {
        // Obtenha as categorias do tipo 'income' (renda)
        $categories = Category::where('category_type', 'income')->get();

        // Converta as categorias para um array de objetos JSON
        $categoriesJson = $categories->map(function ($category) {
            return [
                'type' => $category->type,
                'subtype' => $category->subtype,
            ];
        })->toArray();

        // Passe as categorias em formato JSON para a view
        return view('entradas.form', compact('categoriesJson'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'subtype' => 'required',
            'description' => 'nullable|string',
            'amount' => 'required|numeric',
        ]);

        // Encontre a categoria correspondente à entrada
        $category = Category::where('type', $request->type)
            ->where('subtype', $request->subtype)
            ->first();

        //dd($request->all());
        // Crie uma nova entrada no banco de dados
        Entrada::create([
            'user_id' => auth()->id(),
            'date' => $request->date ?? now(),
            'type' => $request->type,
            'subtype' => $request->subtype,
            'category_type' => $category->category_type,
            'description' => $request->description,
            'amount' => $request->amount,
        ]);

        // Redirecione para a página de sucesso
        return redirect()->route('entradas.form')->with('success', 'Entrada registrada com sucesso.');
    }
}
