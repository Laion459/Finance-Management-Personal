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
            'amount' => 100.00,
            'category_id' => $category->id,
            'payment_method' => 'Cartão de Crédito',
        ];

        // Executa a requisição POST para a rota de store
        $response = $this->actingAs($user)
            ->post(route('saidas.store'), $dados);

        // Verifica se a saída foi criada corretamente no banco de dados
        $this->assertDatabaseHas('saidas', [
            'user_id' => $user->id,
            'date' => $dados['date'],
            'amount' => $dados['amount'],
            'category_id' => $dados['category_id'],
            'payment_method' => $dados['payment_method'],
        ]);

        // Verifica o redirecionamento após o registro
        $response->assertRedirect(route('saidas.form'))
            ->assertSessionHas('success', 'Saída registrada com sucesso!');
    }


    public function test_usuario_nao_pode_registrar_saida_com_dados_invalidos()
    {
        // Cria um usuário e categorias necessárias
        $user = User::factory()->create();
        $category = Category::factory()->create(['category_type' => 'expense']);

        // Dados inválidos para o teste (faltando 'amount')
        $dados = [
            'date' => '2020-10-05',
            'category_id' => $category->id,
            'payment_method' => 'Cartão de Crédito',
        ];

        // Executa a requisição POST para a rota de store
        $response = $this->actingAs($user)
            ->post(route('saidas.store'), $dados);

        // Verifica se houve um erro de validação
        $response->assertSessionHasErrors(['amount']);
    }

    public function test_usuario_ve_excecao_quando_categoria_nao_existe()
    {
        // Cria um usuário
        $user = User::factory()->create();

        // Dados válidos para o teste
        $dados = [
            'date' => '2020-10-05',
            'amount' => 100.00,
            'category_id' => 999, // ID inválido propositalmente
            'payment_method' => 'Cartão de Crédito',
        ];

        // Executa a requisição POST para a rota de store
        $response = $this->actingAs($user)
            ->post(route('saidas.store'), $dados);

        // Verifica se a exceção foi tratada corretamente
        $response->assertSessionHasErrors(['category_id']);
        $this->assertEquals('The selected category id is invalid.', session('errors')->get('category_id')[0]);
    }
}
