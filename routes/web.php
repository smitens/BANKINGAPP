<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvestmentAccountController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TopUpController;
use App\Http\Controllers\TransactionAccountController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\CryptoController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/accounts/create', [AccountController::class, 'create'])->name('accounts.create');
    Route::post('/accounts/store', [AccountController::class, 'store'])->name('accounts.store');
    Route::get('/accounts', [AccountController::class, 'index'])->name('accounts.index');
    Route::delete('/accounts/transaction/{id}', [TransactionAccountController::class, 'destroy'])->name('accounts.transaction.delete');
    Route::delete('/accounts/investment/{id}', [InvestmentAccountController::class, 'destroy'])->name('accounts.investment.delete');
    Route::get('/transaction-accounts/{account}', [TransactionAccountController::class, 'show'])->name('accounts.transaction.show');
    Route::get('/investment-accounts/{account}', [InvestmentAccountController::class, 'show'])->name('accounts.investment.show');
    Route::post('/accounts/transaction-accounts/{id}/share', [TransactionAccountController::class, 'share'])->name('accounts.share.transaction');
    Route::post('/accounts/investment-accounts/{id}/share', [InvestmentAccountController::class, 'share'])->name('accounts.share.investment');

    Route::get('/investment-accounts/{id}/investments', [InvestmentController::class, 'index'])->name('investments.index');
    Route::get('/investment-accounts/{account_id}/investments/{id}', [InvestmentController::class, 'show'])->name('investments.show');
    Route::get('/investments/all', [InvestmentController::class, 'get'])->name('investments.all');
    Route::get('/investments/topup', [TopUpController::class, 'create'])->name('investments.topup.form');
    Route::post('/investments/topup', [TopUpController::class, 'store'])->name('investments.topup');
    Route::post('/cryptos/buy', [InvestmentController::class, 'buy'])->name('investments.buy');
    Route::post('/wallets/{id}/sell', [InvestmentController::class, 'sell'])->name('wallets.sell');
    Route::get('/wallets', [WalletController::class, 'index'])->name('wallets.index');
    Route::get('/wallets/{id}', [WalletController::class, 'show'])->name('wallets.show');

    Route::get('/transaction-accounts/{account}/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions/store', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transaction-accounts/{id}/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/all', [TransactionController::class, 'getAll'])->name('transactions.all');

    Route::get('/cryptos', [CryptoController::class, 'index'])->name('cryptos.index');
    Route::get('/cryptos/{symbol}', [CryptoController::class, 'show'])->name('cryptos.show');

});

require __DIR__.'/auth.php';
