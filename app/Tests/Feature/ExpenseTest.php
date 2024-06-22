<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpenseTest extends TestCase
{
    use RefreshDatabase;

    public function testStoreExpense()
    {
        // Cria um usuário para autenticação
        $user = User::factory()->create();

        // Crie uma categoria de despesa e um método de pagamento
        $expenseCategory = Category::factory()->create([
            'category_type' => 'expense',
        ]);
        $paymentMethod = Category::factory()->create([
            'category_type' => 'payment_method',
        ]);

        // Dados para o request
        $data = [
            'date' => '2024-03-18',
            'amount' => '100.00',
            'category_id' => $expenseCategory->id,
            'payment_method' => $paymentMethod->id,
            'description' => 'Jantar com amigos',
        ];

        // Faz o request autenticado para a rota de registro de despesas
        $response = $this->actingAs($user)
            ->post(route('expenses.store'), $data);

        // Asserções
        $response->assertStatus(302);
        $response->assertRedirect(route('expenses.form'));
        $response->assertSessionHas('success', 'Despesa registrada com sucesso!');

        // Verifica se a despesa foi salva no banco de dados
        $this->assertDatabaseHas('expenses', [
            'user_id' => $user->id,
            'date' => '2024-03-18',
            'amount' => '100.00',
            'category_id' => $expenseCategory->id,
            'description' => 'Jantar com amigos',
            'payment_method' => $paymentMethod->id
        ]);
    }

    public function testStoreExpenseWithInvalidData()
    {
        // Cria um usuário para autenticação
        $user = User::factory()->create();

        // Dados inválidos para o request (faltando campo 'date')
        $data = [
            'amount' => '100.00',
            'category_id' => 1,
            'payment_method' => 1,
            'description' => 'Jantar com amigos',
        ];

        // Faz o request autenticado
        $response = $this->actingAs($user)
            ->post(route('expenses.store'), $data);

        // Asserções
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['date']);

        // Dados inválidos - Valor não numérico
        $data = [
            'date' => '2024-03-18',
            'amount' => 'cem reais', // Valor inválido
            'category_id' => 1,
            'payment_method' => 1,
            'description' => 'Jantar com amigos',
        ];

        $response = $this->actingAs($user)
            ->post(route('expenses.store'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['amount']);

        // Dados inválidos - Categoria inexistente
        $data = [
            'date' => '2024-03-18',
            'amount' => '100.00',
            'category_id' => 999, // Categoria inexistente
            'payment_method' => 1,
            'description' => 'Jantar com amigos',
        ];

        $response = $this->actingAs($user)
            ->post(route('expenses.store'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['category_id']);

        // ... (outros campos a serem validados) ...
    }

    public function testShowExpenseForm()
    {
        // Crie uma categoria de despesa e um método de pagamento
        $expenseCategory = Category::factory()->create([
            'category_type' => 'expense',
        ]);
        $paymentMethod = Category::factory()->create([
            'category_type' => 'payment_method',
        ]);

        // Cria um usuário para autenticação
        $user = User::factory()->create();

        // Faz o request autenticado para a rota do formulário de despesas
        $response = $this->actingAs($user)->get(route('expenses.form'));

        // Asserções
        $response->assertStatus(200);
        $response->assertViewIs('expenses.form');

        // Verifica se os dados necessários para a view estão presentes
        $response->assertViewHas('expenseCategories');
        $response->assertViewHas('paymentCategories');
    }
}
