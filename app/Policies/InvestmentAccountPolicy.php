<?php

namespace App\Policies;

use App\Models\User;
use App\Models\InvestmentAccount;

class InvestmentAccountPolicy
{
    public function view(User $user, InvestmentAccount $account): bool
    {
        return $this->userHasPermission($user, $account, 'view');
    }

    public function full(User $user, InvestmentAccount $account): bool
    {
        return $this->userHasPermission($user, $account, 'full');
    }
    private function userHasPermission(User $user, InvestmentAccount $account, $accessType): bool
    {
        $permission = $account->users()->where('user_id', $user->id)->first();

        return $permission && $permission->pivot->access_type === $accessType;
    }
}
