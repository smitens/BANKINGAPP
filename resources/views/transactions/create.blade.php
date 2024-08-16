<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Transaction') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-semibold mb-4">Create a New Transaction</h1>

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

                    <!-- Transaction Form -->
                    <form action="{{ route('transactions.store') }}" method="POST">
                        @csrf

                        <!-- From Account -->
                        <div class="mb-4">
                            <label for="account_id" class="block text-gray-700">From Account</label>
                            <select name="account_id" id="account_id" class="form-select mt-1 block w-full" required>
                                <option value="" disabled selected>Select an account</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}">
                                        {{ $account->account_number }} ({{ $account->currency }}) - Balance: {{ number_format($account->balance, 2) }}
                                    </option>
                                @endforeach
                            </select>

                            @error('account_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Recipient Account Number -->
                        <div class="mb-4">
                            <label for="recipient_account_number" class="block text-gray-700">Recipient Account Number</label>
                            <input type="text" name="recipient_account_number" id="recipient_account_number" class="form-input mt-1 block w-full" required>
                            @error('recipient_account_number')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Amount -->
                        <div class="mb-4">
                            <label for="amount" class="block text-gray-700">Amount</label>
                            <input type="number" name="amount" id="amount" class="form-input mt-1 block w-full" step="0.01" min="0" required>
                            @error('amount')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="block text-gray-700">Description</label>
                            <textarea name="description" id="description" class="form-textarea mt-1 block w-full"></textarea>
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
                            Submit
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
