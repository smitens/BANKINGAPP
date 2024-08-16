<?php

namespace App\Http\Controllers;

use App\Models\TransactionAccount;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use App\Models\InvestmentAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;

class TopUpController extends Controller
{
    private TransactionService  $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function create(): Application|Factory|View
    {
        $transactionAccounts = TransactionAccount::with('users')->where('user_id', Auth::id())->get();
        $investmentAccounts = InvestmentAccount::with('users')->where('user_id', Auth::id())->get();

        return view('investments.topup', [
            'transactionAccounts' => $transactionAccounts,
            'investmentAccounts' => $investmentAccounts,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'transaction_account_id' => 'required|exists:transaction_accounts,id',
            'investment_account_id' => 'required|exists:investment_accounts,id',
            'description' => 'nullable|string',
            'transaction_fee' => 'nullable|numeric|min:0',
        ]);

        try {
            $this->transactionService->createTopUp($validated);

            return redirect()->route('accounts.investment.show',
                $validated['investment_account_id'])->with('success', 'Top up successful!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
