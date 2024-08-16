<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionAccount;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index($id): RedirectResponse|View
    {
        $transactionAccount = TransactionAccount::with('transactions.creator')->find($id);

        if (!$transactionAccount) {
            return redirect()->back()->withErrors('Transaction account not found');
        }

        $transactions = $transactionAccount->transactions;

        return view('transactions.index', compact('transactionAccount', 'transactions'));
    }

    public function create(): View
    {
        $user = Auth::user();

        $accounts = $user->accounts()->whereHas('users', function($query) {
            $query->whereIn('access_type', ['transfer', 'full']);
        })->get();

        Log::info('User Accounts', ['user' => $user->id, 'accounts' => $accounts]);

        return view('transactions.create', compact('accounts'));
    }


    public function store(Request $request): RedirectResponse
    {
        Log::info('Store method started');

        $data = $request->validate([
            'amount' => 'required|numeric|min:0',
            'account_id' => 'required|exists:transaction_accounts,id',
            'recipient_account_number' => 'required|string',
            'description' => 'nullable|string|max:255',
            'transaction_fee' => 'nullable|numeric|min:0',
        ]);

        Log::info('Store Request Data', $data);

        $fromAccount = TransactionAccount::find($data['account_id']);

        if (!$fromAccount) {
            return redirect()->back()->with('error', 'Sender account not found.');
        }

        if (!Gate::allows('transfer', $fromAccount) && !Gate::allows('full', $fromAccount)) {
            return redirect()->back()->withErrors('You do not have permission to perform this transfer.');
        }

        $recipientAccount = TransactionAccount::where('account_number', $data['recipient_account_number'])->first();

        if (!$recipientAccount) {
            return redirect()->back()->with('error', 'Recipient account not found.');
        }

        Log::info('Adjusted Transaction Data', $data);

        try {
            $this->transactionService->createTransaction($data);
            return redirect()->route('transactions.index', $data['account_id'])->
            with('success', 'Transaction completed successfully.');
        } catch (\Exception $e) {
            Log::error('Transaction failed', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function getAll(): View
    {
        $user = Auth::user();

        $transactions = Transaction::whereIn('account_id', $user->accounts->pluck('id'))
            ->with(['account', 'recipientAccount', 'creator'])
            ->paginate(8);

        return view('transactions.all', ['transactions' => $transactions]);
    }
}
