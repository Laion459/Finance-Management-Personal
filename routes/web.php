<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::middleware(['auth'])->group(function () {
    // Rota para exibir o formulário de registro de despesas
    Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');

    // Rota para lidar com o envio do formulário de registro de despesas
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');

    // Rota para a visualização de relatórios mensais
    Route::get('/reports/monthly', [ReportController::class, 'showMonthlyReports'])->name('reports.monthly');
});


require __DIR__.'/auth.php';
