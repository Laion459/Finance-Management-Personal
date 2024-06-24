<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Category;
use App\Http\Controllers\EntradaController;
use Illuminate\Http\Request;

class EntradaControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_stores_a_new_entry()
    {
        // Crie um usuário fake
        $user = User::factory()->create();

        // Crie uma categoria fake
        $category = Category::factory()->create([
            'type' => 'expense',
            'subtype' => 'food',
            'category_type' => 'food_expense',
        ]);

        // Dados do request
        $data = [
            'type' => 'expense',
            'subtype' => 'food',
            'description' => 'Dinner at restaurant',
            'amount' => 50.00,
        ];

        // Atue como o usuário fake
        $response = $this->actingAs($user)->post(route('entradas.store'), $data);

        // Verifique se a entrada foi criada no banco de dados
        $this->assertDatabaseHas('entradas', [
            'user_id' => $user->id,
            'type' => 'expense',
            'subtype' => 'food',
            'category_type' => 'food_expense',
            'description' => 'Dinner at restaurant',
            'amount' => 50.00,
        ]);

        // Verifique o redirecionamento e a mensagem de sucesso
        $response->assertRedirect(route('entradas.form'));
        $response->assertSessionHas('success', 'Entrada registrada com sucesso.');
    }

    /**
     * Testa se o método create() da EntradaController retorna as categorias de renda corretamente.
     */
    public function test_create_method_returns_income_categories()
    {
        // Mock do Controller e do Request
        $controller = new EntradaController();
        $request = new Request();

        // Simula a execução do método create() do controller
        $response = $controller->create();

        // Obtém o conteúdo da variável categoriesJson da resposta
        $categoriesJson = $response->getData()['categoriesJson'];

        // Categorias esperadas
        $expectedCategories = [
            ['type' => 'Salário', 'subtype' => 'Bônus'],
            ['type' => 'Investimentos', 'subtype' => 'Juros'],
        ];

        // Verifica se a resposta não está vazia
        $this->assertNotEmpty($categoriesJson);

        // Verifica se as categorias esperadas estão presentes no retorno
        foreach ($expectedCategories as $category) {
            $this->assertContainsOnly('array', $categoriesJson);
            $this->assertContains($category, $categoriesJson);
        }
    }

    public function test_store_method_handles_invalid_data()
    {
        // Crie um usuário fake
        $user = User::factory()->create();

        // Dados inválidos (faltando o campo 'amount')
        $data = [
            'type' => 'expense',
            'subtype' => 'food',
            'description' => 'Dinner at restaurant',
        ];

        // Atue como o usuário fake
        $response = $this->actingAs($user)->post(route('entradas.store'), $data);

        // Verifique se há erros de validação retornados
        $response->assertSessionHasErrors('amount');
    }
}
