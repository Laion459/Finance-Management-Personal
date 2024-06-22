<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class EntradaTest extends TestCase
{
    use RefreshDatabase;

    public function test_inserir_entrada_valida()
    {
        // Cria um usuário e autentica
        $user = User::factory()->create();
        $this->actingAs($user);

        // Cria uma categoria
        $category = Category::factory()->create([
            'category_type' => 'income',
            'type' => 'Salário',
            'subtype' => 'Salário Mensal'
        ]);

        // Dados da entrada
        $data = [
            'date' => '2024-03-20',
            'type' => 'Salário',
            'subtype' => 'Salário Mensal',
            'description' => 'Salário de Março',
            'amount' => '3000.00'
        ];

        // Faz a requisição POST
        $response = $this->post(route('entradas.store'), $data);

        // Asserções
        $response->assertRedirect(route('entradas.form'));
        $response->assertSessionHas('success', 'Entrada registrada com sucesso.');
        $this->assertDatabaseHas('entradas', [
            'user_id' => $user->id,
            'date' => '2024-03-20',
            'type' => 'Salário',
            'subtype' => 'Salário Mensal',
            'category_type' => 'income',
            'description' => 'Salário de Março',
            'amount' => '3000.00'
        ]);
    }
}
