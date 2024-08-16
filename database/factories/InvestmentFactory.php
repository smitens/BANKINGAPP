<?php

namespace Database\Factories;

use App\Models\Investment;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\InvestmentAccount;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Investment>
 */
class InvestmentFactory extends Factory
{
    protected $model = Investment::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Retrieve existing transaction accounts
        $userId = User::inRandomOrder()->first()->id ?? User::factory()->create()->id;
        $accountId = InvestmentAccount::inRandomOrder()->first()->id ?? InvestmentAccount::factory()->create()->id;
        $recipientAccountId = InvestmentAccount::inRandomOrder()->first()->id ?? InvestmentAccount::factory()->create()->id;
        return [
            'user_id' => $userId,
            'account_id' => $accountId,
            'type' => $this->faker->randomElement(['crypto', 'stock']),
            'name' => $this->faker->word,
            'amount_invested' => $this->faker->randomFloat(2, 0, 10000),
            'quantity' => $this->faker->randomFloat(2, 0, 100),
            'purchase_price' => $this->faker->randomFloat(2, 0, 1000),
            'current_price' => $this->faker->randomFloat(2, 0, 1000),
            'total_value' => $this->faker->randomFloat(2, 0, 10000),
            'profit_loss' => $this->faker->randomFloat(2, -10000, 10000),
            'purchase_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'status' => $this->faker->randomElement(['bought', 'sold']),
            'investment_fee' => $this->faker->randomFloat(2, 0, 100),
            'description' => $this->faker->sentence,
        ];
    }
}
