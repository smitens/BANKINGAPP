<?php

namespace App\Services;

use App\Models\InvestmentAccount;
use App\Models\Transaction;
use App\Models\TransactionAccount;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class TransactionService
{
    protected  CurrencyConversionService $currencyConversionService;

    public function __construct(CurrencyConversionService $currencyConversionService)
    {
        $this->currencyConversionService = $currencyConversionService;
    }

    public function createTransaction(array $data): Transaction
    {
        $fromAccount = TransactionAccount::find($data['account_id']);
        $toAccount = TransactionAccount::where('account_number', $data['recipient_account_number'])->first();

        if (!$fromAccount || !$toAccount) {
            throw new \Exception('Account not found.');
        }

        if ($fromAccount->balance < $data['amount']) {
            throw new \Exception('Insufficient balance.');
        }

        $amount = $data['amount'];
        $convertedAmount = $fromAccount->currency === $toAccount->currency ? $amount
            : $this->currencyConversionService->convertCurrency($amount, $fromAccount->currency, $toAccount->currency);

        return DB::transaction(function () use ($fromAccount, $toAccount, $amount, $convertedAmount, $data) {
            $fromAccount->balance -= $amount;
            $fromAccount->save();

            $toAccount->balance += $convertedAmount;
            $toAccount->save();

            $transactionSender = Transaction::create([
                'account_id' => $fromAccount->id,
                'type' => 'transfer_out',
                'amount' => $amount,
                'currency' => $fromAccount->currency,
                'recipient_sender_account_id' => $toAccount->id,
                'recipient_sender_account_number' => $toAccount->account_number,
                'recipient_account_type' => 'transaction',
                'description' => $data['description'] ?? '',
                'transaction_date' => now(),
                'status' => 'completed',
                'transaction_fee' => $data['transaction_fee'] ?? 0,
                'user_id' => Auth::id()
            ]);

            $transactionRecipient = Transaction::create([
                'account_id' => $toAccount->id,
                'type' => 'transfer_in',
                'amount' => $convertedAmount,
                'currency' => $toAccount->currency,
                'recipient_sender_account_id' => $fromAccount->id,
                'recipient_sender_account_number' => $fromAccount->account_number,
                'recipient_account_type' => 'transaction',
                'description' => $data['description'] ?? '',
                'transaction_date' => now(),
                'status' => 'completed',
                'transaction_fee' => $data['transaction_fee'] ?? 0,
                'user_id' => Auth::id()
            ]);

            return $transactionSender;
        });
    }

    public function createTopUp(array $data): Transaction
    {
        $fromAccount = TransactionAccount::find($data['transaction_account_id']);
        if (!$fromAccount) {
            throw new \Exception('Source account not found.');
        }

        $investmentAccount = InvestmentAccount::find($data['investment_account_id']);
        if (!$investmentAccount) {
            throw new \Exception('Investment account not found.');
        }

        if ($fromAccount->balance < $data['amount']) {
            throw new \Exception('Insufficient balance.');
        }

        $amount = $data['amount'];
        $convertedAmount = $fromAccount->currency === $investmentAccount->currency
            ? $amount
            : $this->currencyConversionService
                ->convertCurrency($amount, $fromAccount->currency, $investmentAccount->currency);

        return DB::transaction(function () use ($fromAccount, $investmentAccount, $amount, $convertedAmount, $data) {
            $fromAccount->balance -= $amount;
            $fromAccount->save();

            $investmentAccount->balance += $convertedAmount;
            $investmentAccount->save();

            $transactionSender = Transaction::create([
                'account_id' => $fromAccount->id,
                'type' => 'transfer_out',
                'amount' => $amount,
                'currency' => $fromAccount->currency,
                'recipient_sender_account_id' => $investmentAccount->id,
                'recipient_sender_account_number' => $investmentAccount->account_number,
                'recipient_account_type' => 'investment',
                'description' => $data['description'] ?? '',
                'transaction_date' => now(),
                'status' => 'completed',
                'transaction_fee' => $data['transaction_fee'] ?? 0,
                'user_id' => Auth::id()
            ]);

            return $transactionSender;
        });
    }

    public function getTransferCounts(): array
    {
        $userId = Auth::id();

        $transfersInCount = Transaction::where('type', 'transfer_in')
            ->where('user_id', $userId)
            ->count();

        $transfersInAmount = Transaction::where('type', 'transfer_in')
            ->where('user_id', $userId)
            ->sum('amount');

        $transfersOutCount = Transaction::where('type', 'transfer_out')
            ->where('user_id', $userId)
            ->count();

        $transfersOutAmount = Transaction::where('type', 'transfer_out')
            ->where('user_id', $userId)
            ->sum('amount');

        return [
            'transfers_in_count' => $transfersInCount,
            'transfers_out_count' => $transfersOutCount,
            'transfers_in_amount' => $transfersInAmount,
            'transfers_out_amount' => $transfersOutAmount,
        ];
    }

    public function getRecent(): Collection
    {
        $user = Auth::user();

        $tenDaysAgo = Carbon::now()->subDays(10);

        $transactions = Transaction::whereIn('account_id', $user->accounts->pluck('id'))
            ->where('created_at', '>=', $tenDaysAgo)
            ->with(['account', 'creator', 'recipientAccount'])
            ->get();

        return $transactions;
    }
}
