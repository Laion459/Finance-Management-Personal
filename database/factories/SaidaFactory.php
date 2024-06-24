<?php

namespace Database\Factories;

use App\Models\Saida;
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
            'category_id' => 'expense',
            'description' => $this->faker->sentence,
            'payment_method' => $this->faker->randomElement(['Dinheiro','Crédito','pix']),
        ];
    }
}
