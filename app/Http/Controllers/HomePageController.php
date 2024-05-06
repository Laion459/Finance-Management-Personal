<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomePageController extends Controller
{
    // Método para exibir a página inicial
    public function index()
    {
        // Aqui você pode adicionar lógica adicional, se necessário
        return view('home');
    }
}
