<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Wallet Details for') }} {{ $wallet->crypto_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-semibold mb-4">
                        {{ __('Wallet Details') }}
                    </h1>

                    @php
                        $profitLoss = $wallet->aver_profit_loss;
                        $profitLossColor = $profitLoss >= 0 ? 'green' : 'red';
                    @endphp

                    <div class="mb-4">
                        <h2 class="text-xl font-semibold">{{ $wallet->crypto_name }}</h2>
                        <p><strong>Current Price:</strong> {{ $cryptoData['current_price'] ?? 'N/A' }}</p>
                        <p><strong>Total Quantity:</strong> {{ $wallet->total_quantity }}</p>
                        <p><strong>Total Value:</strong> {{ number_format($wallet->total_value, 2) ?? 'N/A' }}</p>
                        <p><strong>Average Buying Price:</strong> {{ number_format($wallet->aver_price, 10) ?? 'N/A' }}</p>
                        <p><strong>Average Profit/Loss (%):</strong>  <span style="color: {{ $profitLossColor }};">
                                {{ number_format($wallet->aver_profit_loss, 2) ?? 'N/A' }}%
                            </span>
                        </p>
                        @if(isset($cryptoData['error']))
                            <p class="text-red-600">{{ $cryptoData['error'] }}</p>
                        @endif
                    </div>

                    <!-- Selling Form -->
                    <form action="{{ route('wallets.sell', $wallet->id) }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="quantity_sold" class="block text-sm font-medium text-gray-700">Quantity to Sell</label>
                            <input type="number" id="quantity_sold" name="quantity_sold" min="0" step="any" required
                                   class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                            @error('quantity_sold')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <x-primary-button>Sell</x-primary-button>

                    </form>

                    <!-- Feedback Messages -->
                    @if(session('success'))
                        <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
