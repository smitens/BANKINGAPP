<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('All Transactions across Your Accounts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @php
                        // Combine and sort transactions by date
                        $allTransactions = $transactions->sortBy('created_at');
                    @endphp

                    @if($allTransactions->isNotEmpty())
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date and Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created By User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Counterparty Account</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($allTransactions as $transaction)
                                @php
                                    $color = $transaction->type === 'transfer_in' ? 'green' : ($transaction->type === 'transfer_out' ? 'red' : 'green');
                                    $symbol = $transaction->type === 'transfer_in' ? '+' : ($transaction->type === 'transfer_out' ? '-' : '');
                                   $mainAccountNumber = $transaction->account->account_number;
                                   $counterpartyAccountNumber = $transaction->recipient_sender_account_number;
                                @endphp
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ ucfirst(str_replace('_', ' ', $transaction->type)) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $mainAccountNumber }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span style="color: {{ $color }};">{{ $symbol . number_format($transaction->amount, 2) }} {{ $transaction->currency }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">No. {{ $transaction->creator->id }}, {{ $transaction->creator->name ?? 'Unknown' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->description }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $counterpartyAccountNumber }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <!-- Pagination Links -->
                        <div class="mt-4">
                            {{ $transactions->links() }}
                        </div>
                    @else
                        <p>No transactions available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
