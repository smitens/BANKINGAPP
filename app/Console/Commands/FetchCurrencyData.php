<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Services\BankOfLatviaService;

class FetchCurrencyData extends Command
{
    protected $signature = 'fetch:currency';
    protected $description = 'Fetch and cache currency exchange rates';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $service = app(BankOfLatviaService::class);

        try {
            $this->info('Fetching currency exchange rates...');

            $data = $service->fetchExchangeRates();

            Cache::put('bank_of_latvia_exchange_rates', $data, 43200);

            Log::info('Currency exchange rates data has been cached successfully.');
            $this->info('Currency exchange rates data has been cached successfully.');

        } catch (\Exception $e) {
            Log::error('An exception occurred while fetching data from Bank of Latvia.', ['exception'
            => $e->getMessage()]);
            $this->error('An exception occurred while fetching data from Bank of Latvia.');
        }
    }
}
