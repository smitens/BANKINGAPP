<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crypto Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Display General Errors -->
                    @if ($errors->has('general'))
                        <div style="color: red; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 1rem; border-radius: 0.25rem; margin-bottom: 1rem;">
                            {{ $errors->first('general') }}
                        </div>
                    @endif

                    @if(!empty($data) && !isset($data['error']))
                        <p><strong>Name:</strong> {{ $data['name'] }}</p>
                        <p><strong>Symbol:</strong> {{ $data['symbol'] }}</p>
                        <p><strong>Price:</strong> ${{ number_format($data['price'], 10) }}</p>
                        <p><strong>Rank:</strong> {{ $data['rank'] }}</p>

                        <!-- Buy Form -->
                        <form action="{{ route('investments.buy') }}" method="POST" class="mt-4">
                            @csrf
                            <input type="hidden" name="type" value="crypto">
                            <input type="hidden" name="symbol" value="{{ $data['symbol'] }}">
                            <div class="mb-4">
                                <label for="account_id" class="block text-sm font-medium text-gray-700">Select Account</label>
                                <select name="account_id" id="account_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->account_number }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                                <input type="number" name="quantity" id="quantity" step="0.01" min="0.01" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div class="mb-4">
                                <label for="investment_fee" class="block text-sm font-medium text-gray-700">Investment Fee (optional)</label>
                                <input type="number" name="investment_fee" id="investment_fee" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div class="mb-4">
                                <label for="description" class="block text-sm font-medium text-gray-700">Description (optional)</label>
                                <textarea name="description" id="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                            </div>
                            <x-primary-button>Buy</x-primary-button>
                        </form>
                    @else
                        <p>{{ $data['message'] ?? 'No data available' }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
