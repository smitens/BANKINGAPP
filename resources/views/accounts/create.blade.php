<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Account') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Display errors -->
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Error encountered!</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <!-- Account Creation Form -->
                    <h3 class="text-lg font-medium mb-4">Create a New Account</h3>

                    <form action="{{ route('accounts.store') }}" method="POST">
                        @csrf

                        <div class="form-group mb-4">
                            <label for="account_type" class="block text-sm font-medium text-gray-700">Account Type</label>
                            <select name="account_type" id="account_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" required>
                                <option value="">Select an account type</option>
                                <option value="transaction">Transaction Account</option>
                                <option value="investment">Investment Account</option>
                            </select>
                            @error('account_type')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="currency" class="block text-sm font-medium text-gray-700">Currency</label>
                            <select name="currency" id="currency" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" required>
                                @foreach($currencyCodes as $code)
                                    <option value="{{ $code }}">{{ $code }}</option>
                                @endforeach
                            </select>
                            @error('currency')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                            <small class="text-gray-500">For investment accounts, the currency will be set to USD by default.</small>
                        </div>

                        <div class="form-group mb-4">
                            <label for="initial_balance" class="block text-sm font-medium text-gray-700">Initial Balance</label>
                            <input type="number" name="initial_balance" id="initial_balance" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" step="0.01" min="0" required>
                            @error('initial_balance')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Create
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
