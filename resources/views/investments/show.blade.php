<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Investment Detail for Investment #') }} {{ $investment->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-semibold mb-4">
                        {{ __('Investment Details') }}
                    </h1>
                    @php
                        $profitLoss = $investment->profit_loss;
                        $profitLossColor = $profitLoss >= 0 ? 'green' : 'red';
                    @endphp

                    <div class="mb-4">
                        <p><strong>Type:</strong> {{ $investment->type }}</p>
                        <p><strong>Symbol:</strong> {{ $investment->name }}</p>
                        <p><strong>Quantity:</strong> {{ $investment->quantity }}</p>
                        <p><strong>Purchase Price:</strong> {{ number_format($investment->purchase_price, 10) }} {{ $investment->currency }}</p>
                        <p><strong>Current Price:</strong> {{ number_format($investment->current_price, 10) }} {{ $investment->currency }}</p>
                        <p><strong>Purchase Date&Time:</strong> {{ $investment->purchase_date }}</p>
                        <p><strong>Total Value:</strong> {{ $investment->total_value }}</p>
                        <p><strong>Profit/Loss:</strong> <span style="color: {{ $profitLossColor }};"> {{ number_format($investment->profit_loss, 10) }}</p>
                        <p><strong>Status:</strong> {{ $investment->status }}</p>
                    </div>
                    @if($errors->any())
                        <div class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
