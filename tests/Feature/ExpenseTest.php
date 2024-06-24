<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Expense;
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
            'date' => '2024-03-20', // Data da despesa
            'amount' => '150.00', // Valor da despesa
            'category_id' => $expenseCategory->id, // ID da categoria de despesa
            'payment_method' => 'credit_card', // Método de pagamento
            'description' => 'Jantar no restaurante' // Descrição da despesa
        ];

        // Faz a requisição POST para criar a despesa
        $response = $this->post(route('expenses.store'), $data);

        // Asserções
        $response->assertRedirect(route('expenses.form')); // Verifica se a requisição foi redirecionada para a página correta
        $response->assertSessionHas('success', 'Despesa registrada com sucesso!'); // Verifica se a mensagem de sucesso foi exibida
        $this->assertDatabaseHas('expenses', [ // Verifica se a despesa foi salva no banco de dados
            'user_id' => $user->id, // ID do usuário
            'date' => '2024-03-20', // Data da despesa
            'amount' => '150.00', // Valor da despesa
            'category_id' => $expenseCategory->id, // ID da categoria de despesa
            'payment_method' => 'credit_card', // Método de pagamento
            'description' => 'Jantar no restaurante' // Descrição da despesa
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
            'date' => '2024-03-20', // Data da despesa
            'amount' => '50.00', // Valor da despesa
            'category_id' => $expenseCategory->id, // ID da categoria de despesa
            'payment_method' => 'cash', // Método de pagamento
            'description' => null // Descrição da despesa (nula)
        ];

        // Faz a requisição POST para criar a despesa
        $response = $this->post(route('expenses.store'), $data);

        // Asserções
        $response->assertRedirect(route('expenses.form')); // Verifica se a requisição foi redirecionada para a página correta
        $response->assertSessionHas('success', 'Despesa registrada com sucesso!'); // Verifica se a mensagem de sucesso foi exibida
        $this->assertDatabaseHas('expenses', [ // Verifica se a despesa foi salva no banco de dados
            'user_id' => $user->id, // ID do usuário
            'date' => '2024-03-20', // Data da despesa
            'amount' => '50.00', // Valor da despesa
            'category_id' => $expenseCategory->id, // ID da categoria de despesa
            'payment_method' => 'cash', // Método de pagamento
            'description' => null // Descrição da despesa (nula)
        ]);
    }

    public function test_registrar_despesa_invalida()
    {
        // Cria um usuário e autentica
        $user = User::factory()->create();
        $this->actingAs($user);

        // Dados da despesa inválida
        $data = [
            'date' => '2024-03-20', // Data da despesa
            'amount' => 'invalid-amount', // Valor da despesa (inválido)
            'category_id' => 99999, // ID da categoria de despesa (inválido)
            'payment_method' => 'credit_card', // Método de pagamento
            'description' => 'Compra inválida' // Descrição da despesa
        ];

        // Faz a requisição POST para criar a despesa
        $response = $this->post(route('expenses.store'), $data);

        // Asserções
        $response->assertSessionHasErrors(['amount', 'category_id']); // Verifica se a requisição retornou erros de validação
        $this->assertDatabaseMissing('expenses', [ // Verifica se a despesa não foi salva no banco de dados
            'user_id' => $user->id, // ID do usuário
            'date' => '2024-03-20', // Data da despesa
            'amount' => 'invalid-amount', // Valor da despesa (inválido)
            'category_id' => 99999, // ID da categoria de despesa (inválido)
            'payment_method' => 'credit_card', // Método de pagamento
            'description' => 'Compra inválida' // Descrição da despesa
        ]);
    }
}
