<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Mockery;
use App\Http\Controllers\ExpenseController;

class ExpenseControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_method_returns_view_with_categories()
    {
        // Executa a rota de exibição do formulário de despesa
        $response = $this->get(route('expenses.form'));

        // Verifica se a resposta é uma instância da view 'expenses.form'
        $response->assertViewIs('expenses.form');

        // Verifica se as variáveis $expenseCategories e $paymentCategories estão sendo passadas para a view
        $response->assertViewHasAll(['expenseCategories', 'paymentCategories']);

        // Pode verificar se as categorias contêm os dados esperados se necessário
        $response->assertViewHas('expenseCategories', function ($categories) {
            return !empty($categories) && count($categories) > 0;
        });

        $response->assertViewHas('paymentCategories', function ($categories) {
            return !empty($categories) && count($categories) > 0;
        });
    }




    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}
