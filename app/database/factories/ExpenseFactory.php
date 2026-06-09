<?php

namespace Database\Factories;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Expense>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Generates a random whole number between 1,000 and 50,000 RWF
            'amount' => $this->faker->numberBetween(1000, 50000),

            // Randomly picks one category from this array
            'category' => $this->faker->randomElement(['Transport', 'Logistics', 'Food & Refreshments', 'Internet Bundle']),

            // Generates a short, realistic sentence for the description
            'description' => $this->faker->sentence(6),
        ];
    }
}
