<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\Wallet;
use App\Services\InvestmentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class WalletController extends Controller
{
    private InvestmentService $investmentService;

    public function __construct(InvestmentService $investmentService)
    {
        $this->investmentService = $investmentService;
    }

    public function get(): array
    {
        $userId = Auth::id();

        $wallets = Wallet::where('user_id', $userId)->get();

        $cryptoData = [];
        $colors = [];
        foreach ($wallets as $wallet) {
            $cryptoInfo = $this->investmentService->getCryptoData($wallet->crypto_name);

            if (!isset($cryptoInfo['error'])) {
                $currentPrice = $cryptoInfo['price'];
                $totalValue = $wallet->total_quantity * $currentPrice;

                $cryptoData[$wallet->crypto_name] = [
                    'total_quantity' => $wallet->total_quantity,
                    'total_value' => $totalValue,
                ];

                $colors[$wallet->crypto_name] = sprintf(
                    'rgba(%d, %d, %d, 0.8)',
                    rand(0, 255), rand(0, 255), rand(0, 255)
                );
            } else {
                $cryptoData[$wallet->crypto_name] = [
                    'total_quantity' => $wallet->total_quantity,
                    'total_value' => 'N/A',
                ];
            }
        }

        return [
            'cryptoData' => $cryptoData,
            'colors' => $colors
        ];
    }

    public function index(): View
    {
        $userId = Auth::id();

        $wallets = Wallet::with('user')->where('user_id', $userId)
            ->get(['crypto_name', 'account_id', 'total_quantity', 'total_invested']);

        $walletsToUpdate = [];
        foreach ($wallets as $wallet) {
            $walletsToUpdate[] = [
                'user_id' => $userId,
                'crypto_name' => $wallet->crypto_name,
                'account_id' => $wallet->account_id
            ];
        }

        $this->investmentService->updateWallets($walletsToUpdate);

        $updatedWallets = Wallet::with('user')->where('user_id', $userId)->get();

        $cryptoData = [];
        foreach ($updatedWallets as $wallet) {
            $cryptoInfo = $this->investmentService->getCryptoData($wallet->crypto_name);

            if (is_array($cryptoInfo) && !isset($cryptoInfo['error'])) {
                $currentPrice = $cryptoInfo['price'];
                $totalValue = $wallet->total_quantity * $currentPrice;
                $profitLoss = $totalValue - $wallet->total_invested;

                $cryptoData[$wallet->crypto_name] = [
                    'current_price' => $currentPrice,
                    'total_quantity' => $wallet->total_quantity,
                    'total_value' => $totalValue,
                    'profit_loss' => $profitLoss,
                ];
            } else {
                $cryptoData[$wallet->crypto_name] = [
                    'current_price' => 'N/A',
                    'total_quantity' => $wallet->total_quantity,
                    'total_value' => 'N/A',
                    'profit_loss' => 'N/A',
                ];
            }
        }

        Log::info('Wallets Before Update:', $wallets->toArray());
        Log::info('Updated Wallets:', $updatedWallets->toArray());
        Log::info('Crypto Data:', $cryptoData);

        return view('wallets.index', ['wallets' => $updatedWallets, 'cryptoData' => $cryptoData]);
    }

    public function show($id): View
    {
        $userId = Auth::id();
        $wallet = Wallet::with('user')->where('id', $id)->where('user_id', $userId)->firstOrFail();

        $cryptoInfo = $this->investmentService->getCryptoData($wallet->crypto_name);
        if (is_array($cryptoInfo) && !isset($cryptoInfo['error'])) {
            $currentPrice = $cryptoInfo['price'];
            $totalValue = $wallet->total_quantity * $currentPrice;
            $profitLoss = $totalValue - $wallet->total_invested;

            $cryptoData = [
                'current_price' => $currentPrice,
                'total_quantity' => $wallet->total_quantity,
                'total_value' => $totalValue,
                'profit_loss' => $profitLoss,
            ];
        } else {
            $cryptoData = ['error' => 'Failed to fetch crypto data'];
        }

        Log::info('Wallet:', $wallet->toArray());
        Log::info('Crypto Data:', $cryptoData);

        return view('wallets.show', ['wallet' => $wallet, 'cryptoData' => $cryptoData]);
    }
}
