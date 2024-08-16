<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transaction Account #') }}{{ $transactionAccount->account_number }}
        </h2>
    </x-slot>

    <!-- Display errors -->
    @if ($errors->any())
        <div style="color: red; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 1rem; border-radius: 0.25rem; margin-bottom: 1rem;">
            <strong class="font-bold text-red-700">Error!</strong>
            <ul class="list-disc pl-5 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">
                    <!-- Account Details -->
                    <div>
                        <p><strong class="font-semibold">Currency:</strong> {{ $transactionAccount->currency }}</p>
                        <p><strong class="font-semibold">Balance:</strong> {{ number_format($transactionAccount->balance, 2) }}</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-4">
                        <a href="{{ route('transactions.index', ['id' => $transactionAccount->id]) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            View Transactions
                        </a>
                        <a href="{{ route('transactions.create', ['account' => $transactionAccount->id]) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            TRANSFER MONEY
                        </a>
                    </div>

                    <!-- Share Account Form -->
                    @can('full', $transactionAccount)
                        <div class="mt-6">
                            <h3 class="font-semibold text-lg text-gray-800">Share Account</h3>
                            <form action="{{ route('accounts.share.transaction', $transactionAccount->id) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="user_id" class="block text-sm font-medium text-gray-700">User Id:</label>
                                    <input type="text" name="user_id" id="user_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 sm:text-sm" required>
                                </div>
                                <div class="mb-4">
                                    <label for="access_type" class="block text-sm font-medium text-gray-700">Access Type:</label>
                                    <select name="access_type" id="access_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 sm:text-sm" required>
                                        <option value="view">View</option>
                                        <option value="transfer">Transfer</option>
                                        <option value="full">Full</option>
                                    </select>
                                </div>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Share Account
                                </button>
                            </form>
                        </div>

                        <!-- Button to Delete Account -->
                        <div class="mt-4">
                            <form action="{{ route('accounts.transaction.delete', $transactionAccount->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this account?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Delete Account
                                </button>
                            </form>
                        </div>

                        <!-- List of Users with Access -->
                        <div class="mt-6">
                            <h3 class="font-semibold text-lg text-gray-800">Shared With:</h3>
                            <ul class="list-disc pl-5">
                                @foreach ($transactionAccount->users as $user)
                                    @if ($user->id !== Auth::id()) <!-- Exclude the current user from the list -->
                                    <li class="mb-2">
                                        {{ $user->email }} - <span class="font-semibold">{{ ucfirst($user->pivot->access_type) }}</span>
                                    </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
