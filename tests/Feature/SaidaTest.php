<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SaidaTest extends TestCase
{
    use RefreshDatabase;


    public function test_usuario_pode_registrar_saida_corretamente()
    {
        // Cria um usuário e categorias necessárias
        $user = User::factory()->create();
        $category = Category::factory()->create(['category_type' => 'expense']);

        // Dados válidos para o teste
        $dados = [
            'date' => '2020-10-05', // Obtém a data atual no formato 'Y-m-d'
            'amount' => 100.00, // Valor da saída
            'category_id' => $category->id, // ID da categoria
            'payment_method' => 'Cartão de Crédito', // Método de pagamento
        ];

        // Executa a requisição POST para a rota de store
        $response = $this->actingAs($user)
            ->post(route('saidas.store'), $dados);

        // Verifica se a saída foi criada corretamente no banco de dados
        $this->assertDatabaseHas('saidas', [
            'user_id' => $user->id, // ID do usuário
            'date' => $dados['date'], // Data da saída
            'amount' => $dados['amount'], // Valor da saída
            'category_id' => $dados['category_id'], // ID da categoria
            'payment_method' => $dados['payment_method'], // Método de pagamento
        ]);

        // Verifica o redirecionamento após o registro
        $response->assertRedirect(route('saidas.form')) // Redireciona para a página de formulário de saída
            ->assertSessionHas('success', 'Saída registrada com sucesso!'); // Verifica se a mensagem de sucesso foi exibida
    }


    public function test_usuario_nao_pode_registrar_saida_com_dados_invalidos()
    {
        // Cria um usuário e categorias necessárias
        $user = User::factory()->create();
        $category = Category::factory()->create(['category_type' => 'expense']);

        // Dados inválidos para o teste (faltando 'amount')
        $dados = [
            'date' => '2020-10-05', // Data da saída
            'category_id' => $category->id, // ID da categoria
            'payment_method' => 'Cartão de Crédito', // Método de pagamento
        ];

        // Executa a requisição POST para a rota de store
        $response = $this->actingAs($user)
            ->post(route('saidas.store'), $dados);

        // Verifica se houve um erro de validação
        $response->assertSessionHasErrors(['amount']); // Verifica se há um erro no campo 'amount'
    }

    public function test_usuario_ve_excecao_quando_categoria_nao_existe()
    {
        // Cria um usuário
        $user = User::factory()->create();

        // Dados válidos para o teste
        $dados = [
            'date' => '2020-10-05', // Data da saída
            'amount' => 100.00, // Valor da saída
            'category_id' => 999, // ID inválido propositalmente
            'payment_method' => 'Cartão de Crédito', // Método de pagamento
        ];

        // Executa a requisição POST para a rota de store
        $response = $this->actingAs($user)
            ->post(route('saidas.store'), $dados);

        // Verifica se a exceção foi tratada corretamente
        $response->assertSessionHasErrors(['category_id']); // Verifica se há um erro no campo 'category_id'
        $this->assertEquals('The selected category id is invalid.', session('errors')->get('category_id')[0]); // Verifica a mensagem de erro específica
    }
}
