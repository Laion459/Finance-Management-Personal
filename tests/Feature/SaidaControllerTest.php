<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Saida;
use App\Models\User;
use App\Models\Notification;
use App\Events\NewNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class SaidaControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa se o método create() da SaidaController retorna as categorias corretamente.
     */
    public function test_create_method_returns_categories()
    {
        // Mock do Controller
        $controller = new \App\Http\Controllers\SaidaController();

        // Simula a execução do método create() do controller
        $response = $controller->create();

        // Verifica se a view 'saidas.form' foi retornada com as categorias corretas
        $responseCategories = $response->getData();
        $this->assertArrayHasKey('expenseCategories', $responseCategories);
        $this->assertArrayHasKey('paymentCategories', $responseCategories);
    }

    /**
     * Testa o método store() da SaidaController com dados válidos.
     */
    public function test_store_method_with_valid_data()
    {
        // Crie um usuário fake
        $user = User::factory()->create();

        // Crie uma categoria fake
        $category = Category::factory()->create();

        // Dados do request
        $data = [
            'date' => now()->format('Y-m-d'),
            'amount' => 100.00,
            'category_id' => $category->id,
            'payment_method' => 'Credit Card',
            'description' => 'Test expense',
        ];

        // Atue como o usuário fake
        $response = $this->actingAs($user)->post(route('saidas.store'), $data);

        // Verifique se a entrada foi criada no banco de dados
        $this->assertDatabaseHas('saidas', [
            'user_id' => $user->id,
            'date' => $data['date'],
            'amount' => $data['amount'],
            'category_id' => $data['category_id'],
            'payment_method' => $data['payment_method'],
            'description' => $data['description'],
        ]);

        // Verifique o redirecionamento e a mensagem de sucesso
        $response->assertRedirect(route('saidas.form'));
        $response->assertSessionHas('success', 'Saída registrada com sucesso!');
    }

    /**
     * Testa o método store() da SaidaController com categoria inválida.
     */
    public function test_store_method_with_invalid_category()
    {
        // Crie um usuário fake
        $user = User::factory()->create();

        // Dados do request com categoria inválida
        $data = [
            'date' => now()->format('Y-m-d'),
            'amount' => 100.00,
            'category_id' => 999, // ID de categoria inválido que não existe
            'payment_method' => 'Credit Card',
            'description' => 'Test expense with invalid category',
        ];

        // Atue como o usuário fake
        $response = $this->actingAs($user)->post(route('saidas.store'), $data);

        // Verifique se há erros de validação retornados
        $response->assertSessionHasErrors(['category_id']);
    }

    /**
     * Testa o método store() da SaidaController para exceção de categoria não encontrada.
     */
    public function test_store_method_handles_category_not_found_exception()
    {
        // Crie um usuário fake
        $user = User::factory()->create();

        // Dados do request com um ID de categoria inválido
        $data = [
            'date' => now()->format('Y-m-d'),
            'amount' => 100.00,
            'category_id' => 999, // ID de categoria inválido que não existe
            'payment_method' => 'Credit Card',
            'description' => 'Test expense with invalid category',
        ];

        // Atue como o usuário fake
        $response = $this->actingAs($user)->post(route('saidas.store'), $data);

        // Verifique se há uma mensagem de erro na sessão
        $response->assertSessionHasErrors(['category_id']);
    }
}
