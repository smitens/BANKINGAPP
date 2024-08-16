<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionAccount extends Model
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
            (User::class, 'transaction_account_user', 'account_id', 'user_id')
            ->withPivot('access_type')
            ->withTimestamps();
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'account_id');
    }
    public function transactionsAsRecipient(): HasMany
    {
        return $this->hasMany(Transaction::class, 'recipient_account_id');
    }

    public function transactionsAsSender(): HasMany
    {
        return $this->hasMany(Transaction::class, 'account_id');
    }

    public function isSharedWith(User $user)
    {
        return $this->users()->where('user_id', $user->id)
            ->whereIn('access_type', ['transfer', 'view'])
            ->exists();
    }

    public function deductAmount($amount)
    {
        if ($this->balance >= $amount) {
            $this->balance -= $amount;
            $this->save();
            return true;
        }
        return false;
    }

    public function addAmount($amount)
    {
        $this->balance += $amount;
        $this->save();
    }
}
