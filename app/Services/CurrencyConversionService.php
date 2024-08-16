<?php

namespace App\Services;

class CurrencyConversionService
{
    protected BankOfLatviaService $bankOfLatviaService;

    public function __construct(BankOfLatviaService $bankOfLatviaService)
    {
        $this->bankOfLatviaService = $bankOfLatviaService;
    }

    public function convertCurrency(float $amount, string $fromCurrency, string $toCurrency): float
    {
        return $this->bankOfLatviaService->convertCurrency($amount, $fromCurrency, $toCurrency);
    }

    public function getCurrencyCodes(): mixed
    {
        return $this->bankOfLatviaService->getExchangeRatesWithCodes();
    }
}
