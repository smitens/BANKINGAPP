<?php

namespace App\Services;

use Illuminate\Foundation\Inspiring;

class InspirationalQuoteService
{
    public function getQuote(): string
    {
        return Inspiring::quote();
    }
}
