<?php

namespace Database\Factories;

use App\Models\Saida;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaidaFactory extends Factory
{
    protected $model = Saida::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'date' => $this->faker->date,
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'category_id' => Category::factory()->create(['category_type' => 'saida'])->id, // Ajuste 'saida' se necessário
            'description' => $this->faker->sentence,
            'payment_method' => $this->faker->randomElement(['credit_card', 'debit_card', 'cash']), // Exemplos de métodos
        ];
    }
}
