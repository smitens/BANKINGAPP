<?php

namespace Database\Factories;

use App\Models\InvestmentAccount;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TransactionAccount>
 */
class InvestmentAccountFactory extends Factory
{
    protected $model = InvestmentAccount::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'account_number' => $this->faker->unique()->bankAccountNumber,
            'currency' => $this->faker->currencyCode,
            'balance' => $this->faker->randomFloat(2, 0, 1000),
            'deleted_at' => null,
        ];
    }
}