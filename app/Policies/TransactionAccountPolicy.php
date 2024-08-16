<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TransactionAccount;

class TransactionAccountPolicy
{
    public function view(User $user, TransactionAccount $account): bool
    {
        return $this->userHasPermission($user, $account, 'view');
    }

    public function transfer(User $user, TransactionAccount $account): bool
    {
        return $this->userHasPermission($user, $account, 'transfer');
    }

    public function full(User $user, TransactionAccount $account): bool
    {
        return $this->userHasPermission($user, $account, 'full');
    }

    private function userHasPermission(User $user, TransactionAccount $account, $accessType): bool
    {
        $permission = $account->users()->where('user_id', $user->id)->first();
        return $permission && $permission->pivot->access_type === $accessType;
    }
}
