<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Your Accounts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Flexbox Layout for Accounts -->
                    <div class="flex flex-wrap gap-8">

                        <!-- Transaction Accounts Section -->
                        <div class="w-full md:w-1/2 lg:w-1/2 bg-gray-100 border border-gray-200 rounded-lg p-4">
                            <h2 class="text-lg font-semibold mb-4">Transaction Accounts</h2>
                            <div class="space-y-4">
                                <!-- Owned Accounts -->
                                @if($transactionAccountsOwned->isNotEmpty())
                                    <div class="bg-white border border-gray-300 rounded-lg p-4">
                                        <h3 class="text-md font-semibold mb-2">Owned</h3>
                                        <ul class="list-disc pl-5">
                                            @foreach ($transactionAccountsOwned as $account)
                                                <li>
                                                    <a href="{{ route('accounts.transaction.show', $account->id) }}" class="text-blue-600 hover:underline">
                                                        Account #{{ $account->account_number }} - {{ $account->currency }}
                                                    </a>
                                                    <span class="text-sm text-gray-500">(Owned)</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <!-- Shared Accounts -->
                                @if($transactionAccountsShared->isNotEmpty())
                                    <div class="bg-white border border-gray-300 rounded-lg p-4">
                                        <h3 class="text-md font-semibold mb-2">Shared</h3>
                                        <ul class="list-disc pl-5">
                                            @foreach ($transactionAccountsShared as $account)
                                                <li>
                                                    <a href="{{ route('accounts.transaction.show', $account->id) }}" class="text-blue-600 hover:underline">
                                                        Account #{{ $account->account_number }} - {{ $account->currency }}
                                                    </a>
                                                    <span class="text-sm text-gray-500">(Shared)</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Investment Accounts Section -->
                        <div class="w-full md:w-1/2 lg:w-1/2 bg-gray-100 border border-gray-200 rounded-lg p-4">
                            <h2 class="text-lg font-semibold mb-4">Investment Accounts</h2>
                            <div class="space-y-4">
                                <!-- Owned Accounts -->
                                @if($investmentAccountsOwned->isNotEmpty())
                                    <div class="bg-white border border-gray-300 rounded-lg p-4">
                                        <h3 class="text-md font-semibold mb-2">Owned</h3>
                                        <ul class="list-disc pl-5">
                                            @foreach ($investmentAccountsOwned as $account)
                                                <li>
                                                    <a href="{{ route('accounts.investment.show', $account->id) }}" class="text-blue-600 hover:underline">
                                                        Account #{{ $account->account_number }} - {{ $account->currency }}
                                                    </a>
                                                    <span class="text-sm text-gray-500">(Owned)</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <!-- Shared Accounts -->
                                @if($investmentAccountsShared->isNotEmpty())
                                    <div class="bg-white border border-gray-300 rounded-lg p-4">
                                        <h3 class="text-md font-semibold mb-2">Shared</h3>
                                        <ul class="list-disc pl-5">
                                            @foreach ($investmentAccountsShared as $account)
                                                <li>
                                                    <a href="{{ route('accounts.investment.show', $account->id) }}" class="text-blue-600 hover:underline">
                                                        Account #{{ $account->account_number }} - {{ $account->currency }}
                                                    </a>
                                                    <span class="text-sm text-gray-500">(Shared)</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>


                    <!-- Create Account Button -->
                    <div class="mt-8">
                        <a href="{{ route('accounts.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Create Account
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
