<?php

use Tests\TestCase;
use App\Http\Controllers\SaidaController;
use Illuminate\Http\Request;
use App\Models\Saida;
use App\Models\Notification;
use App\Events\NewNotification;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
// Corrigido: Importar o Mockery corretamente
use Mockery\MockInterface;

class SaidaControllerTest extends TestCase
{
    public function test_usuario_pode_registrar_saida_unitario()
    {
        // Simula um usuário autenticado
        $user = User::factory()->create();
        Auth::login($user);

        // Dados de requisição simulados
        $request = new Request([
            'date' => '2020-10-05',
            'amount' => 100.00,
            'category_id' => 1,
            'payment_method' => 'Cartão de Crédito',
            'description' => 'Teste de descrição'
        ]);

        // Mock do Model Saida para retornar um objeto com ID
        $mockSaida = $this->mock(Saida::class, function ($mock) use ($user) {
            $mock->shouldReceive('create')->once()->andReturn(
                (object)[
                    'id' => 1,
                    'category_id' => 1,
                    'amount' => 100.00
                ]
            );
        });

        // Aqui fazemos a injeção do mock no container
        $this->instance(Saida::class, $mockSaida);

        Event::fake(); // Previne o disparo real do evento

        $controller = new SaidaController();
        $response = $controller->store($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('saidas.form'), $response->getTargetUrl());

        $response = $this->followRedirects($response);
        $response->assertSessionHas('success', 'Saída registrada com sucesso!');

        // Verifica se a saída foi criada no banco de dados
        $this->assertDatabaseHas('saidas', [
            'user_id' => $user->id,
            'amount' => 100.00,
            'category_id' => 1,
        ]);

        // Verifica se a notificação foi criada no banco de dados
        $this->assertDatabaseHas('notifications', [
            'user_id' => $user->id,
            'message' => 'N: 1 - R$ 100.00'
        ]);

        Event::assertDispatched(NewNotification::class);
    }
}
