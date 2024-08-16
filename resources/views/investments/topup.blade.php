<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Top Up Investment Accounts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-semibold mb-4">Top Up Investment Account</h1>

                    <!-- Display validation errors -->
                    @if($errors->any())
                        <div class="mb-4">
                            <ul class="list-disc pl-5 text-red-500">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Top Up Form -->
                    <form action="{{ route('investments.topup') }}" method="POST">
                        @csrf

                        <!-- Transaction Account -->
                        <div class="mb-4">
                            <label for="transaction_account_id" class="block text-gray-700">Transaction Account</label>
                            <select name="transaction_account_id" id="transaction_account_id" class="form-select mt-1 block w-full" required>
                                <option value="" disabled selected>Select a transaction account</option>
                                @foreach($transactionAccounts as $account)
                                    <option value="{{ $account->id }}">
                                        {{ $account->account_number }} ({{ $account->currency }}) - Balance: {{ number_format($account->balance, 2) }}
                                    </option>
                                @endforeach
                            </select>

                            @error('transaction_account_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Investment Account -->
                        <div class="mb-4">
                            <label for="investment_account_id" class="block text-gray-700">Investment Account</label>
                            <select name="investment_account_id" id="investment_account_id" class="form-select mt-1 block w-full" required>
                                <option value="" disabled selected>Select an investment account</option>
                                @foreach($investmentAccounts as $account)
                                    <option value="{{ $account->id }}">
                                        {{ $account->account_number }} ({{ $account->currency }}) - Balance: {{ number_format($account->balance, 2) }}
                                    </option>
                                @endforeach
                            </select>

                            @error('investment_account_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Amount -->
                        <div class="mb-4">
                            <label for="amount" class="block text-gray-700">Amount</label>
                            <input type="number" name="amount" id="amount" class="form-input mt-1 block w-full" step="0.01" min="0.01" required>
                            @error('amount')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="block text-gray-700">Description</label>
                            <textarea name="description" id="description" class="form-textarea mt-1 block w-full">Top up investment account.
                            </textarea>
                            @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Transaction Fee -->
                        <div class="mb-4">
                            <label for="transaction_fee" class="block text-gray-700">Transaction Fee</label>
                            <input type="number" name="transaction_fee" id="transaction_fee" class="form-input mt-1 block w-full" step="0.01" min="0">
                            @error('transaction_fee')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Top Up
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
