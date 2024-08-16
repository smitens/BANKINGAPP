<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Top Cryptocurrencies') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(!empty($data) && is_array($data) && !isset($data['error']))
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Logo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Symbol</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price (USD)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Market Cap</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Volume (24h)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invest</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($data as $crypto)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $crypto['rank'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <img src="{{ $crypto['logo'] }}" alt="{{ $crypto['name'] }} Logo" class="w-5 h-5">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $crypto['name'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $crypto['symbol'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ number_format($crypto['price'], 10) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ number_format($crypto['market_cap'], 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ number_format($crypto['volume_24h'], 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('cryptos.show', $crypto['symbol']) }}" class="text-blue-600 hover:underline">
                                            <i class="fas fa-dollar-sign"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>No top cryptocurrencies available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
