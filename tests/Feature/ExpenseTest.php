<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpenseTest extends TestCase
{
    use RefreshDatabase;

    public function test_registrar_despesa_valida()
    {
        // Cria um usuário e autentica
        $user = User::factory()->create();
        $this->actingAs($user);

        // Cria uma categoria de despesa
        $expenseCategory = Category::factory()->create([
            'category_type' => 'expense',
            'subtype' => 'Alimentação'
        ]);

        // Dados da despesa
        $data = [
            'date' => '2024-03-20',
            'amount' => '150.00',
            'category_id' => $expenseCategory->id,
            'payment_method' => 'credit_card',
            'description' => 'Jantar no restaurante'
        ];

        // Faz a requisição POST
        $response = $this->post(route('expenses.store'), $data);

        // Debug response content
        $response->dump();

        // Asserções
        $response->assertRedirect(route('expenses.form'));
        $response->assertSessionHas('success', 'Despesa registrada com sucesso!');
        $this->assertDatabaseHas('expenses', [
            'user_id' => $user->id,
            'date' => '2024-03-20',
            'amount' => '150.00',
            'category_id' => $expenseCategory->id,
            'payment_method' => 'credit_card',
            'description' => 'Jantar no restaurante'
        ]);
    }

    public function test_registrar_despesa_sem_descricao()
    {
        // Cria um usuário e autentica
        $user = User::factory()->create();
        $this->actingAs($user);

        // Cria uma categoria de despesa
        $expenseCategory = Category::factory()->create([
            'category_type' => 'expense',
            'subtype' => 'Transporte'
        ]);

        // Dados da despesa sem descrição
        $data = [
            'date' => '2024-03-20',
            'amount' => '50.00',
            'category_id' => $expenseCategory->id,
            'payment_method' => 'cash',
            'description' => null
        ];

        // Faz a requisição POST
        $response = $this->post(route('expenses.store'), $data);

        // Debug response content
        $response->dump();

        // Asserções
        $response->assertRedirect(route('expenses.form'));
        $response->assertSessionHas('success', 'Despesa registrada com sucesso!');
        $this->assertDatabaseHas('expenses', [
            'user_id' => $user->id,
            'date' => '2024-03-20',
            'amount' => '50.00',
            'category_id' => $expenseCategory->id,
            'payment_method' => 'cash',
            'description' => null
        ]);
    }

    public function test_registrar_despesa_invalida()
    {
        // Cria um usuário e autentica
        $user = User::factory()->create();
        $this->actingAs($user);

        // Dados da despesa inválida
        $data = [
            'date' => '2024-03-20',
            'amount' => 'invalid-amount',
            'category_id' => 99999, // ID inválido
            'payment_method' => 'credit_card',
            'description' => 'Compra inválida'
        ];

        // Faz a requisição POST
        $response = $this->post(route('expenses.store'), $data);

        // Debug response content
        $response->dump();

        // Asserções
        $response->assertSessionHasErrors(['amount', 'category_id']);
        $this->assertDatabaseMissing('expenses', [
            'user_id' => $user->id,
            'date' => '2024-03-20',
            'amount' => 'invalid-amount',
            'category_id' => 99999,
            'payment_method' => 'credit_card',
            'description' => 'Compra inválida'
        ]);
    }
}
