<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class BankOfLatviaService
{
    protected $apiUrl = 'https://www.bank.lv/vk/ecb.xml';

    public function fetchExchangeRates(): array
    {
        $response = Http::get($this->apiUrl);

        Log::info('Raw API Response: ' . $response->body());

        if ($response->successful()) {
            $xmlContent = $response->body();
            Log::info('XML Content: ' . $xmlContent);

            $xml = simplexml_load_string($xmlContent);
            if ($xml === false) {
                Log::error('Failed to parse XML.');
                throw new \Exception("Failed to parse XML.");
            }

            return $this->parseExchangeRates($xml);
        }

        Log::error('Failed to fetch exchange rates.');
        throw new \Exception("Failed to fetch exchange rates.");
    }

    private function parseExchangeRates($xml): array
    {
        $rates = [];
        $currencyCodes = [];

        if (isset($xml->Currencies)) {
            $namespaces = $xml->getNamespaces(true);
            $xml->registerXPathNamespace('ns', $namespaces['']);

            foreach ($xml->xpath('//ns:Currency') as $currency) {
                $currencyCode = (string) $currency->ID;
                $rate = (float) $currency->Rate;

                $rates[$currencyCode] = $rate;
                $currencyCodes[] = $currencyCode;
            }
        } else {
            Log::error('Currencies element not found in XML.');
        }

        if (!isset($rates['EUR'])) {
            $rates['EUR'] = 1.0;
            $currencyCodes[] = 'EUR';
        }

        Log::info('Parsed Exchange Rates: ', $rates);
        Log::info('Parsed Currency Codes: ', $currencyCodes);

        return ['rates' => $rates, 'currencyCodes' => $currencyCodes];
    }

    public function getExchangeRatesWithCodes(): mixed
    {
        $data = Cache::remember('bank_of_latvia_exchange_rates', 43200, function () {
            return $this->fetchExchangeRates();
        });

        Log::info('Cached Exchange Rates Data: ', $data);

        return $data['currencyCodes'];
    }

    public function getExchangeRate(string $fromCurrency, string $toCurrency): float|int
    {
        $rates = Cache::remember('bank_of_latvia_exchange_rates', 43200, function () {
            return $this->fetchExchangeRates();
        });

        Log::info('Available Rates: ', $rates);

        if (!isset($rates['rates'][$fromCurrency])) {
            Log::error("Invalid currency code. From: $fromCurrency not found.");
            throw new \Exception("Invalid currency code: $fromCurrency.");
        }

        if (!isset($rates['rates'][$toCurrency])) {
            Log::error("Invalid currency code. To: $toCurrency not found.");
            throw new \Exception("Invalid currency code: $toCurrency.");
        }

        $fromRate = $rates['rates'][$fromCurrency];
        $toRate = $rates['rates'][$toCurrency];

        return $toRate / $fromRate;
    }

    public function convertCurrency(float $amount, string $fromCurrency, string $toCurrency): float
    {
        $rate = $this->getExchangeRate($fromCurrency, $toCurrency);
        return $amount * $rate;
    }
}
