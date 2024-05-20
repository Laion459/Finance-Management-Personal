<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('subtype');
            $table->string('category_type'); // 'payment_method', 'income', 'expense'
            $table->string('description')->nullable(); // Campo para descrições gerais
            $table->timestamps();
        });

        // Inserindo as categorias de meios de pagamento
        DB::table('categories')->insert([
            ['type' => 'PIX', 'subtype' => 'PIX', 'category_type' => 'payment_method', 'description' => 'Pagamentos via PIX', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Crédito', 'subtype' => 'Crédito', 'category_type' => 'payment_method', 'description' => 'Pagamentos com cartão de crédito', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Débito', 'subtype' => 'Débito', 'category_type' => 'payment_method', 'description' => 'Pagamentos com cartão de débito', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Dinheiro', 'subtype' => 'Dinheiro', 'category_type' => 'payment_method', 'description' => 'Pagamentos em dinheiro', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Outro', 'subtype' => 'Outro', 'category_type' => 'payment_method', 'description' => 'Outros métodos de pagamento', 'created_at' => now(), 'updated_at' => now()]
        ]);

        // Inserindo as categorias de entrada
        DB::table('categories')->insert([
            ['type' => 'Salário', 'subtype' => 'Salário Mensal', 'category_type' => 'income', 'description' => 'Salário recebido', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Salário', 'subtype' => 'Bônus', 'category_type' => 'income', 'description' => 'Bônus recebido', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Salário', 'subtype' => 'Participação nos Lucros', 'category_type' => 'income', 'description' => 'Participação nos lucros recebida', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Salário', 'subtype' => 'Horas Extras', 'category_type' => 'income', 'description' => 'Pagamento por horas extras', 'created_at' => now(), 'updated_at' => now()],

            ['type' => 'Investimentos', 'subtype' => 'Dividendos', 'category_type' => 'income', 'description' => 'Dividendos recebidos', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Investimentos', 'subtype' => 'Juros', 'category_type' => 'income', 'description' => 'Juros recebidos', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Investimentos', 'subtype' => 'Ganhos de Capital', 'category_type' => 'income', 'description' => 'Ganhos de capital', 'created_at' => now(), 'updated_at' => now()],

            ['type' => 'Renda Extra', 'subtype' => 'Trabalho Freelance', 'category_type' => 'income', 'description' => 'Recebimento por trabalho freelance', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Renda Extra', 'subtype' => 'Vendas Online', 'category_type' => 'income', 'description' => 'Recebimento por vendas online', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Renda Extra', 'subtype' => 'Aluguel de Imóveis', 'category_type' => 'income', 'description' => 'Recebimento de aluguel de imóveis', 'created_at' => now(), 'updated_at' => now()],

            ['type' => 'Presentes', 'subtype' => 'Dinheiro', 'category_type' => 'income', 'description' => 'Recebimento de dinheiro como presente', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Presentes', 'subtype' => 'Bens de Valor', 'category_type' => 'income', 'description' => 'Recebimento de bens de valor como presente', 'created_at' => now(), 'updated_at' => now()],

            ['type' => 'Outros', 'subtype' => 'Restituição de Imposto', 'category_type' => 'income', 'description' => 'Recebimento de restituição de imposto', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Outros', 'subtype' => 'Prêmios', 'category_type' => 'income', 'description' => 'Recebimento de prêmios', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Outros', 'subtype' => 'Heranças', 'category_type' => 'income', 'description' => 'Recebimento de heranças', 'created_at' => now(), 'updated_at' => now()]
        ]);

        // Inserindo as categorias de saída
        DB::table('categories')->insert([
            ['type' => 'Moradia', 'subtype' => 'Moradia', 'category_type' => 'expense', 'description' => 'Despesas relacionadas à moradia', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Transporte', 'subtype' => 'Transporte', 'category_type' => 'expense', 'description' => 'Despesas relacionadas ao transporte', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Alimentação', 'subtype' => 'Alimentação', 'category_type' => 'expense', 'description' => 'Despesas relacionadas à alimentação', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Educação', 'subtype' => 'Educação', 'category_type' => 'expense', 'description' => 'Despesas relacionadas à educação', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Saúde', 'subtype' => 'Saúde', 'category_type' => 'expense', 'description' => 'Despesas relacionadas à saúde', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Lazer', 'subtype' => 'Lazer', 'category_type' => 'expense', 'description' => 'Despesas relacionadas ao lazer', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Outros', 'subtype' => 'Outros', 'category_type' => 'expense', 'description' => 'Outras despesas', 'created_at' => now(), 'updated_at' => now()]
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
