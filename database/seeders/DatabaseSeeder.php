<?php

namespace Database\Seeders;

use App\Models\Entrada;
use App\Models\Expense;
use App\Models\Saida;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory(10)->create()->each(function (User $user) {
            $entradas = Entrada::factory(10)->make()->toArray();
            $saidas = Saida::factory(5)->make()->toArray();
            $expenses = Expense::factory(5)->make()->toArray();

            $user->entradas()->createMany($entradas);
            $user->saidas()->createMany($saidas);
            $user->expenses()->createMany($expenses);
        });

        // Cria o usuário de teste
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
