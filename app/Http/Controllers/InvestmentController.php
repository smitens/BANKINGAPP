<?php

namespace App\Http\Controllers;

use App\Exceptions\InsufficientBalanceException;
use Illuminate\Http\Request;
use App\Models\InvestmentAccount;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Investment;
use App\Models\Wallet;
use App\Services\InvestmentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class InvestmentController extends Controller
{
    private InvestmentService $investmentService;

    public function __construct(InvestmentService $investmentService)
    {
        $this->investmentService = $investmentService;
    }

    public function buy(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'type' => 'required|string|in:crypto',
            'symbol' => 'required|string',
            'quantity' => 'required|numeric|min:0.01',
            'investment_fee' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'account_id' => 'required|exists:investment_accounts,id',
        ]);

        $user = Auth::user();
        $account = InvestmentAccount::find($data['account_id']);

        if (!$account) {
            return redirect()->back()->withErrors(['account_id' => 'Investment account not found.']);
        }

        $data['user_id'] = $user->id;

        try {
            $this->investmentService->buyCrypto($data);

            return redirect()->route('investments.index', ['id' => $data['account_id']])
                ->with('success', 'Investment successful!');
        } catch (InsufficientBalanceException $e) {
            Log::error('Investment purchase failed', [
                'user_id' => $user->id,
                'account_id' => $data['account_id'],
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->withErrors(['general' => $e->getMessage()]);
        } catch (\Exception $e) {
            Log::error('Investment purchase failed', [
                'user_id' => $user->id,
                'account_id' => $data['account_id'],
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->
            withErrors(['general' => 'An error occurred while processing your investment. Please try again.']);
        }
    }

    public function sell(Request $request, $id): RedirectResponse
    {
        $userId = Auth::id();
        $wallet = Wallet::where('id', $id)->where('user_id', $userId)->firstOrFail();

        $request->validate([
            'quantity_sold' => 'required|numeric|min:0',
        ]);

        $quantitySold = $request->input('quantity_sold');

        if ($wallet->total_quantity < $quantitySold) {
            return redirect()->back()->withErrors(['quantity_sold' => 'Cannot sell more than owned.']);
        }

        try {
            $cryptoInfo = $this->investmentService->getCryptoData($wallet->crypto_name);
            if (isset($cryptoInfo['error'])) {
                throw new \Exception("Failed to fetch current price.");
            }

            $currentPrice = $cryptoInfo['price'];

            $this->investmentService->sellCrypto($wallet, $quantitySold, $currentPrice);


            return redirect()->route('wallets.show', $wallet->id)->with('success', 'Crypto sold successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function show($account_id, $id): RedirectResponse|View
    {
        $investmentAccount = InvestmentAccount::with('investments')->find($account_id);

        if (!$investmentAccount) {
            return redirect()->back()->withErrors('Investment account not found');
        }

        $investment = $investmentAccount->investments->where('id', $id)->first();

        if (!$investment) {
            return redirect()->back()->withErrors('Investment not found');
        }

        Log::info('Showing investment before update:', $investment->toArray());

        $this->investmentService->updateCurrentPricesAndValues($investment);

        Log::info('Showing investment after update:', $investment->toArray());

        return view('investments.show', ['investment' => $investment, 'investmentAccount' => $investmentAccount]);
    }

    public function index($id): RedirectResponse|View
    {
        $investmentAccount = InvestmentAccount::with('investments')->find($id);

        if (!$investmentAccount) {
            return redirect()->back()->withErrors('Investment account not found');
        }

        foreach ($investmentAccount->investments as $investment) {
            $this->investmentService->updateCurrentPricesAndValues($investment);
        }

        $updatedInvestments = $investmentAccount->investments;

        Log::info('Investment Account:', $investmentAccount->toArray());
        Log::info('Updated Investments:', $updatedInvestments->toArray());

        return view('investments.index', compact('investmentAccount', 'updatedInvestments'));
    }

    public function get(): View
    {
        $user = Auth::user();

        $investments = Investment::whereIn('account_id', $user->accounts->pluck('id'))
            ->with(['account'])
            ->get();

        return view('investments.all', ['investments' => $investments]);
    }
}
