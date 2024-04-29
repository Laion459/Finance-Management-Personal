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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('gender'); // Sexo do usuário
            $table->date('date_of_birth'); // Data de nascimento do usuário
            $table->string('profession')->nullable(); // Profissão do usuário (opcional)
            $table->integer('number_of_children')->default(0); // Quantidade de filhos
            $table->string('marital_status')->default('single'); // Estado civil (padrão: solteiro)
            // Campos de endereço
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('street')->nullable();
            $table->string('number')->nullable();
            $table->string('complement')->nullable(); // Complemento do endereço (opcional)
            $table->rememberToken();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
