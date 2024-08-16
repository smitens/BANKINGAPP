<?php

namespace App\Providers;

use App\Models\InvestmentAccount;
use App\Models\TransactionAccount;
use App\Policies\InvestmentAccountPolicy;
use App\Policies\TransactionAccountPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        TransactionAccount::class => TransactionAccountPolicy::class,
        InvestmentAccount::class => InvestmentAccountPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        Gate::define('full', [InvestmentAccountPolicy::class, 'delete']);
        Gate::define('view', [InvestmentAccountPolicy::class, 'view']);
        Gate::define('invest', [InvestmentAccountPolicy::class, 'invest']);

        Gate::define('full', [TransactionAccountPolicy::class, 'delete']);
        Gate::define('view', [TransactionAccountPolicy::class, 'view']);
        Gate::define('transfer', [TransactionAccountPolicy::class, 'transfer']);
    }
}
