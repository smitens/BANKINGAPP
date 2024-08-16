<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CurrencyConversionService;
use App\Models\TransactionAccount;
use App\Models\InvestmentAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class AccountController extends Controller
{
    protected CurrencyConversionService $currencyConversionService;

    public function __construct(CurrencyConversionService $currencyConversionService)
    {
        $this->currencyConversionService = $currencyConversionService;
    }
    public function create(): View
    {
        $currencyCodes = $this->currencyConversionService->getCurrencyCodes();

        return view('accounts.create', compact('currencyCodes'));
    }

    protected function validateAccount(Request $request): array
    {
        return $request->validate([
            'account_type' => 'required|in:transaction,investment',
            'initial_balance' => 'required|numeric|min:0',
            'currency' => 'required_if:account_type,transaction|string|max:3',
        ], [
            'account_type.required' => 'The account type is required.',
            'account_type.in' => 'The account type must be either transaction or investment.',
            'initial_balance.required' => 'The initial balance is required.',
            'initial_balance.numeric' => 'The initial balance must be a number.',
            'initial_balance.min' => 'The initial balance must be at least 0.',
            'currency.required_if' => 'The currency is required for transaction accounts.',
            'currency.string' => 'The currency must be a string.',
            'currency.max' => 'The currency must not exceed 3 characters.',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $this->validateAccount($request);

        $currency = $validatedData['account_type'] === 'investment' ? 'USD' : $validatedData['currency'];

        $accountNumber = Str::random(10);

        $account = $validatedData['account_type'] === 'transaction'
            ? TransactionAccount::create([
                'user_id' => auth()->id(),
                'account_number' => $accountNumber,
                'currency' => $currency,
                'balance' => $validatedData['initial_balance'],
            ])
            : InvestmentAccount::create([
                'user_id' => auth()->id(),
                'account_number' => $accountNumber,
                'currency' => $currency,
                'balance' => $validatedData['initial_balance'],
            ]);

        $account->users()->attach(auth()->id(), ['access_type' => 'full']);
        return redirect()->route('accounts.index')->with('success', 'Account created successfully!');
    }

    private function fetchAndSeparateAccounts($user): array
    {
        $allTransactionAccounts = TransactionAccount::with('users')->where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->orWhereHas('users', function ($subQuery) use ($user) {
                    $subQuery->where('user_id', $user->id)
                        ->whereIn('access_type', ['transfer', 'view', 'owner']);
                });
        })->get();

        $transactionAccountsOwned = $allTransactionAccounts->filter(function ($account) use ($user) {
            return $account->user_id == $user->id;
        });

        $transactionAccountsShared = $allTransactionAccounts->filter(function ($account) use ($user) {
            return $account->users->contains($user) && $account->user_id != $user->id;
        });

        $allInvestmentAccounts = InvestmentAccount::with('users')->where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->orWhereHas('users', function ($subQuery) use ($user) {
                    $subQuery->where('user_id', $user->id)
                        ->whereIn('access_type', ['transfer', 'view', 'owner']);
                });
        })->get();

        $investmentAccountsOwned = $allInvestmentAccounts->filter(function ($account) use ($user) {
            return $account->user_id == $user->id;
        });

        $investmentAccountsShared = $allInvestmentAccounts->filter(function ($account) use ($user) {
            return $account->users->contains($user) && $account->user_id != $user->id;
        });

        return compact(
            'transactionAccountsOwned', 'transactionAccountsShared',
            'investmentAccountsOwned', 'investmentAccountsShared'
        );
    }

    public function index(): View
    {
        $user = Auth::user();
        $accounts = $this->fetchAndSeparateAccounts($user);

        return view('accounts.index', array_merge($accounts, ['user' => $user]));
    }

    public function getAccountData(): array
    {
        $user = Auth::user();
        return $this->fetchAndSeparateAccounts($user);
    }
}
