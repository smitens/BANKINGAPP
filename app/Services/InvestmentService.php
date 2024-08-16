<?php

namespace App\Services;

use App\Exceptions\InsufficientBalanceException;
use App\Models\Investment;
use App\Models\InvestmentAccount;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class InvestmentService
{
    private CryptoDataService $cryptoDataService;

    public function __construct(CryptoDataService $cryptoDataService)
    {
        $this->cryptoDataService = $cryptoDataService;
    }

    public function buyCrypto(array $data): Investment
    {
        // Fetch crypto data
        $cryptoData = $this->cryptoDataService->getCryptoData($data['symbol']);

        // Check for errors in the response
        if (isset($cryptoData['error']) && $cryptoData['error']) {
            throw new \Exception($cryptoData['message']);
        }

        // Ensure $cryptoData is an array and contains the expected fields
        if (!is_array($cryptoData) || !isset($cryptoData['price'])) {
            throw new \Exception('Unexpected data format received for crypto data.');
        }

        $purchasePrice = $cryptoData['price'];
        $totalInvested = $data['quantity'] * $purchasePrice;

        $account = InvestmentAccount::find($data['account_id']);
        if ($account->balance < $totalInvested) {
            throw new InsufficientBalanceException("Insufficient balance in the investment account.");
        }
        $account->balance -= $totalInvested;
        $account->save();

        $investment = Investment::create([
            'user_id' => $data['user_id'],
            'account_id' => $data['account_id'],
            'type' => 'crypto',
            'name' => $data['symbol'],
            'amount_invested' => $totalInvested,
            'quantity' => $data['quantity'],
            'purchase_price' => $purchasePrice,
            'current_price' => $purchasePrice,
            'total_value' => $totalInvested,
            'profit_loss' => 0,
            'purchase_date' => Carbon::now(),
            'status' => 'bought',
            'investment_fee' => $data['investment_fee'] ?? 0,
            'description' => $data['description'] ?? '',
        ]);

        $this->updateWallet($data['user_id'], $data['symbol'], $data['account_id']);

        return $investment;
    }


    public function sellCrypto(Wallet $wallet, float $quantitySold, float $currentPrice): Wallet
    {
        Log::info('Wallet Before Sale:', $wallet->toArray());

        if ($wallet->total_quantity < $quantitySold) {
            throw new \Exception("Cannot sell more than owned.");
        }

        $costOfSoldQuantity = $quantitySold * $wallet->aver_price;
        $totalEarned = $quantitySold * $currentPrice;
        $profitLoss = $totalEarned - $costOfSoldQuantity;
        $profitLossPercentage = ($wallet->total_quantity > 0)
            ? (($currentPrice - $wallet->aver_price) / $wallet->aver_price) * 100
            : 0;

        $account = InvestmentAccount::find($wallet->account_id);
        $account->balance += $totalEarned;
        $account->save();

        Investment::create([
            'user_id' => $wallet->user_id,
            'account_id' => $wallet->account_id,
            'type' => 'crypto',
            'name' => $wallet->crypto_name,
            'amount_invested' => $costOfSoldQuantity,
            'quantity' => $quantitySold,
            'purchase_price' => $wallet->aver_price,
            'current_price' => $currentPrice,
            'total_value' => $totalEarned,
            'profit_loss' => $profitLoss,
            'purchase_date' => Carbon::now(),
            'status' => 'sold',
            'investment_fee' => 0,
            'description' => 'Sold crypto',
        ]);

        $wallet->total_quantity -= $quantitySold;
        $wallet->total_value = $wallet->total_quantity * $currentPrice;
        $wallet->aver_profit_loss = $profitLossPercentage;

        if ($wallet->total_quantity > 0) {
            $remainingValue = $wallet->total_quantity * $wallet->aver_price;
            $wallet->aver_price = $remainingValue / $wallet->total_quantity;
        } else {
            $wallet->aver_price = 0;
        }

        if (!$wallet->save()) {
            Log::error('Failed to save wallet after sale.', ['wallet' => $wallet->toArray()]);
            throw new \Exception('Failed to save wallet after sale.');
        }

        Log::info('Updated Wallet After Sale:', $wallet->toArray());

        return $wallet;
    }

    public function getCryptoData(string $name): array
    {
        return $this->cryptoDataService->getCryptoData($name);
    }

    public function updateWallet(int $userId, string $cryptoName, int $accountId): void
    {
        $investments = Investment::where('user_id', $userId)
            ->where('type', 'crypto')
            ->where('name', $cryptoName)
            ->where('account_id', $accountId)
            ->get();

        $boughtInvestments = $investments->where('status', 'bought');
        $soldInvestments = $investments->where('status', 'sold');

        $totalBoughtQuantity = $boughtInvestments->sum('quantity');
        $totalBoughtInvested = $boughtInvestments->sum('amount_invested');
        $totalBoughtValue = $boughtInvestments->sum('total_value');

        $totalSoldQuantity = $soldInvestments->sum('quantity');
        $totalSoldInvested = $soldInvestments->sum('amount_invested');
        $totalSoldValue = $soldInvestments->sum('total_value');

        $netQuantity = $totalBoughtQuantity - $totalSoldQuantity;
        $netInvested = $totalBoughtInvested - $totalSoldInvested;
        $netValue = $totalBoughtValue - $totalSoldValue;

        $newAverageBuyingPrice = $netQuantity > 0
            ? $boughtInvestments->sum(fn($investment)
            => $investment->quantity * $investment->purchase_price) / $totalBoughtQuantity
            : 0;

        $totalProfitLoss = $netValue - $netInvested;
        $averageProfitLossPercentage = $netInvested > 0
            ? ($totalProfitLoss / $netInvested) * 100
            : 0;

        Wallet::updateOrCreate(
            [
                'user_id' => $userId,
                'crypto_name' => $cryptoName,
                'account_id' => $accountId,
            ],
            [
                'total_quantity' => $netQuantity,
                'total_invested' => $netInvested,
                'total_value' => $netValue,
                'aver_price' => $newAverageBuyingPrice,
                'aver_profit_loss' => $averageProfitLossPercentage,
            ]
        );
    }

    public function updateWallets(array $walletsToUpdate): void
    {
        foreach ($walletsToUpdate as $walletData) {
            $this->updateWallet(
                $walletData['user_id'],
                $walletData['crypto_name'],
                $walletData['account_id']
            );
        }
    }

    public function updateCurrentPricesAndValues(Investment $investment): Investment
    {
        if ($investment->type == 'crypto') {
            $cryptoData = $this->cryptoDataService->getCryptoData($investment->name);
            if (isset($cryptoData['error'])) {
                throw new \Exception($cryptoData['message']);
            }

            $investment->current_price = $cryptoData['price'];
            Log::info("Updated price for {$investment->name}: {$investment->current_price}");
        }

        $investment->total_value = $investment->quantity * $investment->current_price;
        $investment->profit_loss = ($investment->current_price - $investment->purchase_price) * $investment->quantity;

        Log::info("Updated investment values - Total Value: {$investment->total_value},
        Profit/Loss: {$investment->profit_loss}");

        $investment->save();

        return $investment;
    }
    public function getRecent(): Collection
    {
        $user = Auth::user();

        $tenDaysAgo = Carbon::now()->subDays(10);

        $investments = Investment::whereIn('account_id', $user->accounts->pluck('id'))
            ->where('created_at', '>=', $tenDaysAgo)
            ->with(['account'])
            ->get();

        return $investments;
    }
}
