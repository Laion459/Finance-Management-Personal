<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\EntradaController;
use App\Http\Controllers\SaidaController;
use App\Http\Controllers\NotificationController;




Route::get('/', function () {
    return view('welcome');
})->name('welcome');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');

Route::middleware(['auth'])->group(function () {
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);


    // Rota para a página inicial após o login
    Route::get('/home', [HomePageController::class, 'index'])->name('home');

    // Rota para exibir o formulário de registro de despesas
    Route::get('/expenses/form', [ExpenseController::class, 'create'])->name('expenses.form');

    // Rota para lidar com o envio do formulário de registro de despesas
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');

    // Rota para a visualização de relatórios mensais
    Route::get('/monthly/reports', [ReportController::class, 'showMonthlyReports'])->name('reports.monthly');

    // Rota para visualização de acompanhamento de orçamento
    Route::get('/budget/tracking', [ReportController::class, 'showBudgetTracking'])->name('budget-tracking');

    // Rotas para cadastro de entradas
    Route::get('/entradas/form', [EntradaController::class, 'create'])->name('entradas.form');
    Route::post('/entradas', [EntradaController::class, 'store'])->name('entradas.store');

    // Rotas para cadastro de saídas
    Route::get('/saidas/form', [SaidaController::class, 'create'])->name('saidas.form');
    Route::post('/saidas', [SaidaController::class, 'store'])->name('saidas.store');
});

require __DIR__ . '/auth.php';
