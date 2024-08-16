<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany; // Import the HasMany class

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the transaction accounts for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactionAccounts(): HasMany
    {
        return $this->hasMany(TransactionAccount::class);
    }

    /**
     * Get the investment accounts for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function investmentAccounts(): HasMany
    {
        return $this->hasMany(InvestmentAccount::class);
    }

    public function wallets(): HasMany
    {
        return $this->hasMany(Wallet::class);
    }

    public function accounts(): BelongsToMany
    {
        return $this
            ->belongsToMany
            (
                TransactionAccount::class,
                'transaction_account_user',
                'user_id',
                'account_id'
            )
            ->withPivot('access_type')
            ->withTimestamps();
    }
}
