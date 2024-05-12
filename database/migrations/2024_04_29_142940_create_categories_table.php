<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });

        // Inserindo as categorias de pagamento
        DB::table('categories')->insert([
            [
                'name' => 'PIX',
                'description' => 'Pagamentos via PIX',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Crédito',
                'description' => 'Pagamentos com cartão de crédito',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Débito',
                'description' => 'Pagamentos com cartão de débito',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Dinheiro',
                'description' => 'Pagamentos em dinheiro',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Outro',
                'description' => 'Outros métodos de pagamento',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Inserindo as categorias de entrada
        DB::table('categories')->insert([
            [
                'name' => 'Salário',
                'description' => 'Salário recebido',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Investimento',
                'description' => 'Rendimento de investimento',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Empréstimo',
                'description' => 'Valor recebido como empréstimo',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Outros',
                'description' => 'Outras formas de entrada de dinheiro',
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Adicionando as categorias de saída
            [
                'name' => 'Moradia',
                'description' => 'Despesas relacionadas à moradia',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Transporte',
                'description' => 'Despesas relacionadas ao transporte',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Alimentação',
                'description' => 'Despesas relacionadas à alimentação',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Saúde',
                'description' => 'Despesas relacionadas à saúde',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Educação',
                'description' => 'Despesas relacionadas à educação',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Lazer',
                'description' => 'Despesas relacionadas ao lazer',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Outros',
                'description' => 'Outras despesas',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
