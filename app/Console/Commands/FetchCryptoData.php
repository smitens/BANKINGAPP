<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Services\CoinPaprikaService;

class FetchCryptoData extends Command
{
    protected $signature = 'fetch:crypto';
    protected $description = 'Fetch and cache top cryptocurrencies data';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $service = app(CoinPaprikaService::class);
        $limit = 20;

        try {
            $data = $service->getTopCryptos($limit);

            if (isset($data['error']) && $data['error']) {
                Log::error('Failed to fetch data from CoinPaprika.', ['error' => $data['message']]);
                $this->error('Failed to fetch data from CoinPaprika.');
                return;
            }

            Cache::put('top_cryptos_' . $limit, $data);

            Log::info('Top cryptocurrencies data has been cached successfully.');
            $this->info('Top cryptocurrencies data has been cached successfully.');

        } catch (\Exception $e) {
            Log::error('An exception occurred while fetching data from CoinPaprika.', ['exception'
            => $e->getMessage()]);
            $this->error('An exception occurred while fetching data from CoinPaprika.');
        }
    }
}
