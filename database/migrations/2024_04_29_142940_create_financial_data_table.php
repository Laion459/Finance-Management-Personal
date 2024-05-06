<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->decimal('base_salary', 10, 2)->nullable();
            $table->decimal('extra_income', 10, 2)->default(0);

            // Campos de despesas
            $table->decimal('water', 10, 2)->default(0);
            $table->decimal('electricity', 10, 2)->default(0);
            $table->decimal('rent', 10, 2)->default(0);
            $table->decimal('gas', 10, 2)->default(0);
            $table->decimal('bus', 10, 2)->default(0);
            $table->decimal('school', 10, 2)->default(0);
            $table->decimal('groceries', 10, 2)->default(0);
            $table->decimal('donations', 10, 2)->default(0);
            $table->decimal('taxes_out', 10, 2)->default(0);
            $table->decimal('taxes_refund', 10, 2)->default(0);
            $table->decimal('savings', 10, 2)->default(0);
            $table->decimal('travel_planned', 10, 2)->default(0);
            $table->decimal('travel_done', 10, 2)->default(0);

            // Campos de parcelamento
            $table->decimal('installment_payment', 10, 2)->default(0);
            $table->decimal('installment_amount', 10, 2)->default(0);
            $table->integer('installment_remaining')->default(0);
            $table->string('installment_description')->nullable();

            // Campos de investimento
            $table->decimal('investments', 10, 2)->default(0);
            $table->string('investment_category')->nullable();
            $table->date('investment_date')->nullable();
            $table->decimal('investment_interest_rate', 5, 2)->nullable();
            $table->integer('investment_min_time')->nullable();
            $table->string('investment_dividends')->nullable();
            $table->string('investment_description')->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_data');
    }
};
