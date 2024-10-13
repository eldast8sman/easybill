<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(['Credit', 'Debit']);
        $amount = $this->faker->randomFloat(2, 100, 1000);
        $balance_before = $this->faker->randomFloat(2, 100, 1000);
        $balance_after = ($type == 'Credit') ? ($balance_before + $amount) : ($balance_before - $amount);
        return [
            'user_id' => User::factory(),
            'type' => $type,
            'amount' => $amount,
            'remarks' => $this->faker->sentence(),
            'balance_before' => $balance_before,
            'balance_after' => $balance_after,
        ];
    }
}
