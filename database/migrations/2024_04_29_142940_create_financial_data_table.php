<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('financial_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->decimal('base_salary', 10, 2)->nullable(); // Salário base
            $table->decimal('extra_income', 10, 2)->default(0); // Entradas extras
            // Campos para diferentes tipos de saídas
            $table->decimal('water', 10, 2)->default(0); // Conta de água
            $table->decimal('electricity', 10, 2)->default(0); // Conta de luz
            $table->decimal('rent', 10, 2)->default(0); // Aluguel
            $table->decimal('gas', 10, 2)->default(0); // Gastos com gasolina
            $table->decimal('bus', 10, 2)->default(0); // Gastos com transporte público
            $table->decimal('school', 10, 2)->default(0); // Gastos com educação/escola
            $table->decimal('groceries', 10, 2)->default(0); // Gastos com mercado
            $table->decimal('donations', 10, 2)->default(0); // Doações
            $table->decimal('taxes_out', 10, 2)->default(0); // Impostos pagos
            $table->decimal('taxes_refund', 10, 2)->default(0); // Impostos a restituir
            $table->decimal('savings', 10, 2)->default(0); // Valor em poupança
            $table->decimal('travel_planned', 10, 2)->default(0); // Gastos com viagens planejadas
            $table->decimal('travel_done', 10, 2)->default(0); // Gastos com viagens realizadas
            // Despesas com parcelamentos
            $table->decimal('installment_payment', 10, 2)->default(0); // Total de prestações pagas
            $table->decimal('installment_amount', 10, 2)->default(0); // Valor da prestação
            $table->integer('installment_remaining')->default(0); // Prestações restantes
            $table->string('installment_description')->nullable(); // Descrição do parcelamento
            // Investimentos
            $table->decimal('investments', 10, 2)->default(0); // Valor total de investimentos
            $table->string('investment_category')->nullable(); // Categoria do investimento
            $table->date('investment_date')->nullable(); // Data de aplicação do investimento
            $table->decimal('investment_interest_rate', 5, 2)->nullable(); // Taxa de juros ao ano
            $table->integer('investment_min_time')->nullable(); // Tempo mínimo de aplicação
            $table->string('investment_dividends')->nullable(); // Dividendos
            $table->string('investment_description')->nullable(); // Descrição do investimento
            $table->timestamps();

            // Chave estrangeira
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_data');
    }
};
