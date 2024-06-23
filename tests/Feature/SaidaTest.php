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


    /** @test */
    public function usuario_pode_registrar_saida_corretamente()
    {
        var_dump(openssl_get_cert_locations());
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
}
