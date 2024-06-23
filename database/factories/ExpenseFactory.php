<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExpenseFactory extends Factory
{
    use HasFactory;
    protected $model = Expense::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'date' => $this->faker->date,
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'category_id' => Category::factory()->create(['category_type' => 'expense'])->id,
            'description' => $this->faker->sentence,
            'payment_method' => 'credit_card',
        ];
    }
}
