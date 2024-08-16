<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvestmentAccount extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'user_id',
        'account_number',
        'currency',
        'balance',

    ];

    public function users(): BelongsToMany
    {
        return $this
            ->belongsToMany
            (User::class, 'investment_account_user', 'account_id', 'user_id')
            ->withPivot('access_type')
            ->withTimestamps();
    }
    public function investments(): HasMany
    {
        return $this->hasMany(Investment::class, 'account_id');
    }

    public function isSharedWith(User $user): bool
    {
        return $this->users()->where('user_id', $user->id)
            ->whereIn('access_type', ['view'])
            ->exists();
    }

    public function addAmount($amount): void
    {
        $this->balance += $amount;
        $this->save();
    }
}
