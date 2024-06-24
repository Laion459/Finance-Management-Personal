<?php

// Declara o namespace para os testes
namespace Tests\Feature;

// Importa os modelos necessários
use App\Models\User;
use App\Models\Category;

// Importa a funcionalidade para reinicializar o banco de dados
use Illuminate\Foundation\Testing\RefreshDatabase;

// Importa a classe base para testes
use Tests\TestCase;



class EntradaTest extends TestCase
{
    // Adiciona o traço RefreshDatabase para reinicializar o banco de dados antes de cada teste
    use RefreshDatabase;
    
    // Declara o teste para inserir uma entrada válida
    public function test_inserir_entrada_valida()
    {
        // Cria um usuário e autentica
        $user = User::factory()->create();
        $this->actingAs($user);

        // Cria uma categoria
        $category = Category::factory()->create([
            'category_type' => 'income', // Tipo de categoria: entrada
            'type' => 'Salário', // Tipo de entrada
            'subtype' => 'Salário Mensal' // Subtipo de entrada
        ]);

        // Dados da entrada
        $data = [
            'date' => '2024-03-20', // Data da entrada
            'type' => 'Salário', // Tipo de entrada
            'subtype' => 'Salário Mensal', // Subtipo de entrada
            'description' => 'Salário de Março', // Descrição da entrada
            'amount' => '3000.00' // Valor da entrada
        ];

        // Faz a requisição POST para criar a entrada
        $response = $this->post(route('entradas.store'), $data);

        // Verifica se a requisição foi redirecionada para a página correta
        $response->assertRedirect(route('entradas.form'));

        // Verifica se a mensagem de sucesso foi exibida
        $response->assertSessionHas('success', 'Entrada registrada com sucesso.');

        // Verifica se a entrada foi salva no banco de dados
        $this->assertDatabaseHas('entradas', [
            'user_id' => $user->id, // ID do usuário que criou a entrada
            'date' => '2024-03-20', // Data da entrada
            'type' => 'Salário', // Tipo de entrada
            'subtype' => 'Salário Mensal', // Subtipo de entrada
            'category_type' => 'income', // Tipo de categoria: entrada
            'description' => 'Salário de Março', // Descrição da entrada
            'amount' => '3000.00' // Valor da entrada
        ]);
    }

    // Declara o teste para inserir uma entrada sem descrição
    public function test_inserir_entrada_sem_descricao()
    {
        // Cria um usuário e autentica
        $user = User::factory()->create();
        $this->actingAs($user);

        // Cria uma categoria
        $category = Category::factory()->create([
            'category_type' => 'income', // Tipo de categoria: entrada
            'type' => 'Salário', // Tipo de entrada
            'subtype' => 'Salário Mensal' // Subtipo de entrada
        ]);

        // Dados da entrada sem descrição
        $data = [
            'date' => '2024-03-20', // Data da entrada
            'type' => 'Salário', // Tipo de entrada
            'subtype' => 'Salário Mensal', // Subtipo de entrada
            'description' => null, // Descrição da entrada (nula)
            'amount' => '3000.00' // Valor da entrada
        ];

        // Faz a requisição POST para criar a entrada
        $response = $this->post(route('entradas.store'), $data);

        // Verifica se a requisição foi redirecionada para a página correta
        $response->assertRedirect(route('entradas.form'));

        // Verifica se a mensagem de sucesso foi exibida
        $response->assertSessionHas('success', 'Entrada registrada com sucesso.');

        // Verifica se a entrada foi salva no banco de dados
        $this->assertDatabaseHas('entradas', [
            'user_id' => $user->id, // ID do usuário que criou a entrada
            'date' => '2024-03-20', // Data da entrada
            'type' => 'Salário', // Tipo de entrada
            'subtype' => 'Salário Mensal', // Subtipo de entrada
            'category_type' => 'income', // Tipo de categoria: entrada
            'description' => null, // Descrição da entrada (nula)
            'amount' => '3000.00' // Valor da entrada
        ]);
    }

    // Declara o teste para inserir uma entrada inválida
    public function test_inserir_entrada_invalida()
    {
        // Cria um usuário e autentica
        $user = User::factory()->create();
        $this->actingAs($user);

        // Dados da entrada inválida
        $data = [
            'date' => '2024-03-20', // Data da entrada
            'type' => 'Salário', // Tipo de entrada
            'subtype' => 'Salário Mensal', // Subtipo de entrada
            'description' => 'Salário de Março', // Descrição da entrada
            'amount' => 'invalid-amount' // Valor da entrada (inválido)
        ];

        // Faz a requisição POST para criar a entrada
        $response = $this->post(route('entradas.store'), $data);

        // Verifica se a requisição retornou erros de validação
        $response->assertSessionHasErrors(['amount']); // Verifica se há erros no campo 'amount'

        // Verifica se a entrada não foi salva no banco de dados
        $this->assertDatabaseMissing('entradas', [
            'user_id' => $user->id, // ID do usuário que criou a entrada
            'date' => '2024-03-20', // Data da entrada
            'type' => 'Salário', // Tipo de entrada
            'subtype' => 'Salário Mensal', // Subtipo de entrada
            'description' => 'Salário de Março', // Descrição da entrada
            'amount' => 'invalid-amount' // Valor da entrada (inválido)
        ]);
    }
}
