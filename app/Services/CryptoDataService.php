<?php

namespace App\Services;

class CryptoDataService
{
    private CoinPaprikaService $coinPaprikaService;

    public function __construct(CoinPaprikaService $coinPaprikaService)
    {
        $this->coinPaprikaService = $coinPaprikaService;
    }

    public function getTopCryptos(int $limit = 20): array
    {
        return $this->coinPaprikaService->getTopCryptos($limit);
    }

    public function getCryptoData(string $symbol): array
    {
        return $this->coinPaprikaService->getCryptoData($symbol);
    }
}
