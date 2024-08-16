<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CoinPaprikaService
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.coinpaprika.com/v1/',
            'timeout' => 10.0,
        ]);
    }

    public function getTopCryptos(int $limit = 20): array
    {
        $cacheKey = 'top_cryptos_' . $limit;
        $cachedData = Cache::get($cacheKey);

        if ($cachedData) {
            Log::info('Cache hit for top cryptocurrencies data.', ['cacheKey' => $cacheKey]);
            return $cachedData;
        }

        Log::info('Cache miss for top cryptocurrencies data.', ['cacheKey' => $cacheKey]);

        try {
            $response = $this->client->request('GET', 'coins');
            $data = json_decode($response->getBody(), true);

            if ($response->getStatusCode() !== 200) {
                Log::error('Failed to get data from CoinPaprika', ['status_code'
                => $response->getStatusCode()]);
                return ['error' => true, 'message' => 'Failed to get data from CoinPaprika'];
            }

            $topCoins = array_slice($data, 0, $limit);
            $result = [];

            foreach ($topCoins as $coin) {
                try {
                    $coinDetailsResponse = $this->client->request('GET', 'coins/' . $coin['id']);
                    $coinDetails = json_decode($coinDetailsResponse->getBody(), true);

                    $tickerDetailsResponse = $this->client->request('GET', 'tickers/' . $coin['id']);
                    $tickerDetails = json_decode($tickerDetailsResponse->getBody(), true);

                    $result[] = [
                        'id' => $coin['id'],
                        'logo' => $coinDetails['logo'] ?? 'default_logo.png',
                        'name' => $coinDetails['name'] ?? 'Unknown',
                        'symbol' => $coinDetails['symbol'] ?? 'N/A',
                        'price' => $tickerDetails['quotes']['USD']['price'] ?? 'N/A',
                        'rank' => $tickerDetails['rank'] ?? 'N/A',
                        'market_cap' => $tickerDetails['quotes']['USD']['market_cap'] ?? 'N/A',
                        'volume_24h' => $tickerDetails['quotes']['USD']['volume_24h'] ?? 'N/A',
                    ];
                } catch (GuzzleException $e) {
                    Log::error('Failed to fetch coin or ticker details', ['id' => $coin['id'], 'error'
                    => $e->getMessage()]);
                }
            }

            Cache::put($cacheKey, $result, now()->addMinutes(5));
            Log::info('Top cryptocurrencies data has been cached successfully.',
                ['cacheKey' => $cacheKey, 'data' => $result]);
            return $result;

        } catch (GuzzleException $e) {
            Log::error('Failed to make HTTP request', ['error' => $e->getMessage()]);
            return ['error' => true, 'message' => 'Failed to make HTTP request'];
        }
    }

    public function getCryptoData(string $symbol): array
    {
        $topCryptos = $this->getTopCryptos();

        foreach ($topCryptos as $crypto) {
            if (strtolower($crypto['symbol']) === strtolower($symbol)) {
                return $crypto;
            }
        }

        $errorResponse = ['error' => true, 'message' => 'Coin with symbol ' . $symbol . ' not found in top cryptocurrencies'];
        Log::warning('Crypto data not found', ['symbol' => $symbol, 'response' => $errorResponse]);
        return $errorResponse;
    }
}
