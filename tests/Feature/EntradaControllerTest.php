<?php

namespace Tests\Unit;

use Tests\TestCase;
use Mockery;
use Illuminate\Http\Request;
use App\Models\Entrada;
use App\Models\Category;
use App\Http\Controllers\EntradaController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class EntradaControllerTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
    }

    public function testStore()
    {
        // Mock do Request
        $requestMock = Mockery::mock(Request::class);
        $requestMock->shouldReceive('validate')
            ->once()
            ->with([
                'type' => 'required',
                'subtype' => 'required',
                'description' => 'nullable|string',
                'amount' => 'required|numeric',
            ])
            ->andReturn(true);

        $requestMock->shouldReceive('all')->andReturn([
            'type' => 'type1',
            'subtype' => 'subtype1',
            'description' => 'description',
            'amount' => 100,
            'date' => null,
        ]);

        $requestMock->shouldReceive('type')->andReturn('type1');
        $requestMock->shouldReceive('subtype')->andReturn('subtype1');
        $requestMock->shouldReceive('description')->andReturn('description');
        $requestMock->shouldReceive('amount')->andReturn(100);
        $requestMock->shouldReceive('date')->andReturn(null);

        // Mock do Category
        $categoryMock = Mockery::mock('alias:App\Models\Category');
        $categoryMock->shouldReceive('where')
            ->with('type', 'type1')
            ->andReturnSelf();
        $categoryMock->shouldReceive('where')
            ->with('subtype', 'subtype1')
            ->andReturnSelf();
        $categoryMock->shouldReceive('first')
            ->once()
            ->andReturn((object)['category_type' => 'income']);

        // Mock do Auth
        Auth::shouldReceive('id')->andReturn(1);

        // Mock do model Entrada
        $entradaMock = Mockery::mock('alias:App\Models\Entrada');
        $entradaMock->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($data) {
                return $data['user_id'] === 1 &&
                       $data['date'] instanceof \Illuminate\Support\Carbon &&
                       $data['type'] === 'type1' &&
                       $data['subtype'] === 'subtype1' &&
                       $data['category_type'] === 'income' &&
                       $data['description'] === 'description' &&
                       $data['amount'] === 100;
            }))
            ->andReturn(true);

        // Mock do Redirect
        Redirect::shouldReceive('route')
            ->once()
            ->with('entradas.form')
            ->andReturnSelf();
        Redirect::shouldReceive('with')
            ->once()
            ->with('success', 'Entrada registrada com sucesso.')
            ->andReturnSelf();

        // Instancia o controller
        $controller = new EntradaController();

        // Chama o método store e verifica o resultado
        $response = $controller->store($requestMock);

        // Verifica o redirecionamento
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
    }
}
