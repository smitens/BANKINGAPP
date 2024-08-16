<?php

namespace Database\Factories;

use App\Models\InvestmentAccount;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TransactionAccount;
use App\Models\Transaction;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Retrieve existing transaction accounts or create new ones if none exist
        $userId = User::inRandomOrder()->first()->id ?? User::factory()->create()->id;
        $accountId = TransactionAccount::inRandomOrder()->first()->id ?? TransactionAccount::factory()->create()->id;

        // Decide the type of recipient account and fetch or create accordingly
        $recipientAccountType = $this->faker->randomElement(['investment', 'transaction']);
        $recipientSenderAccountId = null;
        $recipientSenderAccountNumber = null;

        if ($recipientAccountType === 'investment') {
            $recipientAccount = InvestmentAccount::inRandomOrder()->first() ?? InvestmentAccount::factory()->create();
            $recipientSenderAccountId = $recipientAccount->id;
            $recipientSenderAccountNumber = $recipientAccount->account_number; // Assuming `InvestmentAccount` has `account_number`
        } else {
            $recipientAccount = TransactionAccount::inRandomOrder()->first() ?? TransactionAccount::factory()->create();
            $recipientSenderAccountId = $recipientAccount->id;
            $recipientSenderAccountNumber = $recipientAccount->account_number;
        }

        return [
            'user_id' => $userId,
            'account_id' => $accountId,
            'type' => $this->faker->randomElement(['transfer_out', 'transfer_in']),
            'amount' => $this->faker->randomFloat(2, 0, 10000),
            'currency' => $this->faker->currencyCode,
            'recipient_sender_account_id' => $recipientSenderAccountId,
            'recipient_account_type' => $recipientAccountType,
            'recipient_sender_account_number' => $recipientSenderAccountNumber, // Updated column name
            'description' => $this->faker->sentence,
            'transaction_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'status' => $this->faker->randomElement(['pending', 'completed', 'failed']),
            'transaction_fee' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}
