<?php

namespace App\Http\Controllers;

use App\Services\InspirationalQuoteService;
use App\Services\InvestmentService;
use App\Services\TransactionService;
use Illuminate\View\View;

class DashboardController extends Controller
{

    protected InvestmentService $investmentService;
    protected InspirationalQuoteService $quoteService;
    protected AccountController $accountController;
    protected TransactionService  $transactionService;
    protected WalletController $walletController;

    public function __construct(

        InvestmentService $investmentService,
        InspirationalQuoteService $quoteService,
        AccountController $accountController,
        TransactionService $transactionService,
        WalletController $walletController
    )
    {
        $this->investmentService = $investmentService;
        $this->quoteService = $quoteService;
        $this->accountController = $accountController;
        $this->transactionService = $transactionService;
        $this->walletController = $walletController;
    }

    public function index(): View
    {
        $transactions = $this->transactionService->getRecent();
        $investments = $this->investmentService->getRecent();
        $quote = $this->quoteService->getQuote();
        $transferCounts = $this->transactionService->getTransferCounts();


        $wallets = $this->walletController->index();
        $cryptoData = $wallets['cryptoData'];
        $chartLabels = json_encode(array_keys($cryptoData));
        $chartData = json_encode(array_column($cryptoData, 'total_value'));


        $accountData = $this->accountController->getAccountData();

        $walletData = $this->walletController->get();


        $transactionAccountsOwned = $accountData['transactionAccountsOwned'];
        $transactionAccountsShared = $accountData['transactionAccountsShared'];
        $investmentAccountsOwned = $accountData['investmentAccountsOwned'];
        $investmentAccountsShared = $accountData['investmentAccountsShared'];

        return view('dashboard', compact(
            'transactions',
            'investments',
            'quote',
            'transactionAccountsOwned',
            'transactionAccountsShared',
            'investmentAccountsOwned',
            'investmentAccountsShared',
            'transferCounts',
            'cryptoData',
            'chartLabels',
            'chartData',
            'walletData'
        ));
    }
}
