<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CoinPaprikaService;
use App\Services\BankOfLatviaService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(CoinPaprikaService::class, function ($app) {
            return new CoinPaprikaService();
        });

        $this->app->singleton(BankOfLatviaService::class, function ($app) {
            return new BankOfLatviaService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
