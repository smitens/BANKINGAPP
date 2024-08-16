<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CurrencyConversionService;
use App\Models\TransactionAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;


class TransactionAccountController extends Controller
{
    protected CurrencyConversionService $currencyConversionService;

    public function __construct(CurrencyConversionService $currencyConversionService)
    {
        $this->currencyConversionService = $currencyConversionService;
    }

    public function share(Request $request, $accountId):RedirectResponse
    {
        Log::info('Share Transaction Request', [
            'user_id' => $request->input('user_id'),
            'access_type' => $request->input('access_type'),
            'account_id' => $accountId,
        ]);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'access_type' => 'required|in:view,transfer,full',
        ]);

        Log::info('Validation Success', $validated);

        $user = Auth::user();
        $account = TransactionAccount::find($accountId);

        if (!$account) {
            Log::error('Transaction account not found', ['account_id' => $accountId]);
            return redirect()->back()->withErrors('Transaction account not found.');
        }

        if (!Gate::allows('full', $account)) {
            Log::warning('User does not have permission to share account', [
                'user_id' => $user->id,
                'account_id' => $accountId,
            ]);
            return redirect()->back()->withErrors('You do not have permission to share this account.');
        }

        Log::info('Permission check passed', [
            'user_id' => $user->id,
            'account_id' => $accountId,
        ]);

        try {
            $account->users()->syncWithoutDetaching([
                $request->input('user_id') => ['access_type' => $request->input('access_type')]
            ]);
            Log::info('Transaction account shared successfully', [
                'account_id' => $accountId,
                'shared_with_user_id' => $request->input('user_id'),
                'access_type' => $request->input('access_type')
            ]);
        } catch (\Exception $e) {
            Log::error('Error sharing transaction account', [
                'account_id' => $accountId,
                'user_id' => $request->input('user_id'),
                'access_type' => $request->input('access_type'),
                'error' => $e->getMessage(),
            ]);
            return redirect()->back()->withErrors('An error occurred while sharing the transaction account.');
        }

        return redirect()->back()->with('success', 'Transaction account shared successfully!');
    }

    public function show($id): RedirectResponse|View
    {
        $transactionAccount = TransactionAccount::with('users')
            ->where('id', $id)
            ->whereHas('users', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->first();

        if ($transactionAccount) {
            return view('accounts.transaction.show', compact('transactionAccount'));
        } else {
            return redirect()->back()->
            withErrors('Transaction account not found or you do not have permission to view it');
        }
    }

    public function destroy($id): RedirectResponse
    {
        $account = TransactionAccount::with('users')->find($id);

        if ($account->balance > 0) {
            return redirect()->back()->
            withErrors('Account cannot be deleted because it has a balance greater than zero.');
        }

        if ($account && Gate::allows('full', $account)) {
            $account->delete();
            return redirect()->route('accounts.index')->
            with('success', 'Transaction account deleted successfully!');
        } else {
            return redirect()->back()->
            withErrors('Transaction account not found or you do not have permission to delete it');
        }
    }
}
