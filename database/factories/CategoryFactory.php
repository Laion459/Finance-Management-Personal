<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * Define the default state of the factory.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'category_type' => $this->faker->randomElement(['expense', 'income', 'payment_method']),
            'type' => $this->faker->word,
            'subtype' => $this->faker->word,
        ];
    }
}
