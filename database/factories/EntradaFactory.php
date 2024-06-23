<?php

namespace Database\Factories;

use App\Models\Entrada; // Importe o modelo Entrada corretamente
use Illuminate\Database\Eloquent\Factories\Factory;

class EntradaFactory extends Factory
{
    /**
     * O nome do modelo que esta factory está atrelada.
     *
     * @var string
     */
    protected $model = Entrada::class;

    /**
     * Defina os atributos default do modelo que serão gerados pela factory.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(), // Relação com o usuário
            'date' => $this->faker->date(),
            'type' => $this->faker->randomElement(['Salário', 'Freelance', 'Outro']),
            'subtype' => $this->faker->word(),
            'category_type' => $this->faker->randomElement(['Renda Fixa', 'Renda Variável', 'Outras']),
            'description' => $this->faker->sentence(),
            'amount' => $this->faker->randomFloat(2, 500, 5000),
        ];
    }
}
