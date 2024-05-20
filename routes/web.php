<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\EntradaController;
use App\Http\Controllers\SaidaController;
use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;




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

    // Rota para buscar as ultimas compras
    Route::get('/ultimas-compras', [ReportController::class, 'getUltimasCompras'])->name('ultimas-compras');
});


Route::middleware('cors')->group(function () {
    Route::get('/api/users', function (Request $request) {
        // ...
    });

    Route::post('/api/posts', function (Request $request) {
        // ...
    });


    // Rota para notificação
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead']);

    Route::post('/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
});

require __DIR__ . '/auth.php';
