<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-l text-gray-800 leading-tight">
            Here’s an inspiring quote for You:
        </h2>
        <div class="mt-2">
            <span class="font-bold">{!! $quote !!}</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                    <!-- Crypto Wallets Chart -->
                    <div class="bg-gray-100 p-6 rounded-lg shadow-md">
                        <h3 class="text-lg font-semibold mb-4">Crypto Wallets</h3>

                        <!-- Chart Container -->
                        <div class="relative">
                            <canvas id="walletsChart" class="w-full h-64"></canvas>
                        </div>

                        <!-- Legend Container -->
                        <div class="mt-6">
                            @foreach($walletData['cryptoData'] as $cryptoName => $data)
                                <div class="flex items-center mb-2">
                                    <div class="w-4 h-4 mr-2" style="background-color: {{ $walletData['colors'][$cryptoName] }};"></div>
                                    <div class="flex-grow">
                                        <span class="font-medium">{{ $cryptoName }}:</span>
                                        <span class="font-medium">Qty: {{ number_format($data['total_quantity'], 2) }}</span>
                                        <span class="font-medium">Value: €{{ number_format($data['total_value'], 2) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- JavaScript for Chart -->
                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                        <script>
                            const ctx = document.getElementById('walletsChart').getContext('2d');

                            // Prepare chart data
                            const chartLabels = {!! json_encode(array_keys($walletData['cryptoData'])) !!};
                            const chartData = {!! json_encode(array_column($walletData['cryptoData'], 'total_value')) !!};
                            const chartColors = {!! json_encode($walletData['colors']) !!};

                            new Chart(ctx, {
                                type: 'doughnut',
                                data: {
                                    labels: chartLabels,
                                    datasets: [{
                                        label: 'Total Value',
                                        data: chartData,
                                        backgroundColor: Object.values(chartColors),
                                        borderColor: Object.values(chartColors).map(color => color.replace('0.8', '1')),
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                        legend: {
                                            display: false // Hide default legend
                                        },
                                        tooltip: {
                                            callbacks: {
                                                label: function(tooltipItem) {
                                                    const label = tooltipItem.label || '';
                                                    const value = tooltipItem.raw || 0;
                                                    return `${label}: €${value.toLocaleString()}`;
                                                }
                                            }
                                        }
                                    }
                                }
                            });
                        </script>
                    </div>

                    <!-- Transfers Summary -->
                    <div style="background-color: #f9fafb; padding: 1.5rem; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                        <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem;">Transfers Summary</h3>

                        <!-- Summary Data -->
                        <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                            <div style="width: 48%; padding-right: 1rem;">
                                <div style="background-color: #d1fae5; color: #16a34a; padding: 1rem; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                                    <h4 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem;">Transfers In</h4>
                                    <p style="font-size: 1.25rem; font-weight: 600;">Count: {{ $transferCounts['transfers_in_count'] }}</p>
                                    <p style="font-size: 1.25rem; font-weight: 600;">Amount: €{{ number_format($transferCounts['transfers_in_amount'], 2) }}</p>
                                </div>
                            </div>
                            <div style="width: 48%; padding-left: 1rem;">
                                <div style="background-color: #fee2e2; color: #dc2626; padding: 1rem; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                                    <h4 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem;">Transfers Out</h4>
                                    <p style="font-size: 1.25rem; font-weight: 600;">Count: {{ $transferCounts['transfers_out_count'] }}</p>
                                    <p style="font-size: 1.25rem; font-weight: 600;">Amount: €{{ number_format($transferCounts['transfers_out_amount'], 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Visualization Using Bars -->
                        <div style="display: flex; flex-direction: column;">
                            <div style="margin-bottom: 1rem;">
                                <h4 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem;">Transfers In</h4>
                                <div style="position: relative; width: 100%; background-color: #d1fae5; height: 24px; border-radius: 9999px; overflow: hidden;">
                                    <div style="position: absolute; top: 0; left: 0; height: 100%; background-color: #16a34a; width: {{ $transferCounts['transfers_in_count'] }}%;"></div>
                                    <div style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">{{ $transferCounts['transfers_in_count'] }}</div>
                                </div>
                                <p style="font-size: 0.875rem; color: #4b5563; margin-top: 0.25rem;">Total: €{{ number_format($transferCounts['transfers_in_amount'], 2) }}</p>
                            </div>
                            <div>
                                <h4 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem;">Transfers Out</h4>
                                <div style="position: relative; width: 100%; background-color: #fee2e2; height: 24px; border-radius: 9999px; overflow: hidden;">
                                    <div style="position: absolute; top: 0; left: 0; height: 100%; background-color: #dc2626; width: {{ $transferCounts['transfers_out_count'] }}%;"></div>
                                    <div style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">{{ $transferCounts['transfers_out_count'] }}</div>
                                </div>
                                <p style="font-size: 0.875rem; color: #4b5563; margin-top: 0.25rem;">Total: €{{ number_format($transferCounts['transfers_out_amount'], 2) }}</p>
                            </div>
                        </div>
                    </div>


                    <!-- Recent Transactions -->
                    <div class="bg-gray-100 p-6 rounded-lg shadow-md">
                        <h3 class="text-lg font-semibold mb-4">Recent Transactions</h3>
                        <ul class="list-disc pl-5">
                            @foreach ($transactions as $transaction)
                                <li>
                                    Trans.No.{{ $transaction->id }}:
                                    <!-- Determine color and symbol based on transaction type -->
                                        <?php
                                        $color = $transaction->type === 'transfer_in' ? 'green' : ($transaction->type === 'transfer_out' ? 'red' : 'green');
                                        $symbol = $transaction->type === 'transfer_in' ? '+' : ($transaction->type === 'transfer_out' ? '-' : '');
                                        ?>
                                    <span style="color: {{ $color }};">{{ $symbol }}</span>
                                    {{ number_format($transaction->amount, 2) }}{{$transaction->currency}} - {{ $transaction->created_at->format('Y-m-d') }}
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Transaction Account Balances Overview -->
                    <div class="bg-gray-100 p-6 rounded-lg shadow-md">
                        <h3 class="text-lg font-semibold mb-4">Transaction Account Balances</h3>

                        <h4 class="text-md font-semibold mb-2">Owned Accounts</h4>
                        @if($transactionAccountsOwned->isNotEmpty())
                            <div class="space-y-2">
                                @foreach ($transactionAccountsOwned as $account)
                                    <div class="flex justify-between">
                                        <span class="font-medium">{{ $account->account_number }}</span>
                                        <span class="font-medium">€{{ number_format($account->balance, 2) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p>No owned transaction accounts.</p>
                        @endif

                        <h4 class="text-md font-semibold mt-4 mb-2">Shared Accounts</h4>
                        @if($transactionAccountsShared->isNotEmpty())
                            <div class="space-y-2">
                                @foreach ($transactionAccountsShared as $account)
                                    <div class="flex justify-between">
                                        <span class="font-medium">{{ $account->account_number }}</span>
                                        <span class="font-medium">€{{ number_format($account->balance, 2) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p>No shared transaction accounts.</p>
                        @endif
                    </div>

                    <!-- Investment Account Balances Overview -->
                    <div class="bg-gray-100 p-6 rounded-lg shadow-md">
                        <h3 class="text-lg font-semibold mb-4">Investment Account Balances</h3>

                        <h4 class="text-md font-semibold mb-2">Owned Accounts</h4>
                        @if($investmentAccountsOwned->isNotEmpty())
                            <div class="space-y-2">
                                @foreach ($investmentAccountsOwned as $account)
                                    <div class="flex justify-between">
                                        <span class="font-medium">{{ $account->account_number }}</span>
                                        <span class="font-medium">€{{ number_format($account->balance, 2) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p>No owned investment accounts.</p>
                        @endif

                        <h4 class="text-md font-semibold mt-4 mb-2">Shared Accounts</h4>
                        @if($investmentAccountsShared->isNotEmpty())
                            <div class="space-y-2">
                                @foreach ($investmentAccountsShared as $account)
                                    <div class="flex justify-between">
                                        <span class="font-medium">{{ $account->account_number }}</span>
                                        <span class="font-medium">€{{ number_format($account->balance, 2) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p>No shared investment accounts.</p>
                        @endif
                    </div>

                    <!-- Recent Investments -->
                    <div class="bg-gray-100 p-6 rounded-lg shadow-md">
                        <h3 class="text-lg font-semibold mb-4">Recent Investments</h3>
                        <ul class="list-disc pl-5">
                            @foreach ($investments as $investment)
                                <li>
                                    Invest.No.{{ $investment->id }}:
                                    {{$investment->name}} - {{$investment->status}} - {{ $investment->created_at->format('Y-m-d') }}
                                </li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
