<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Investment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'account_id',
        'type',
        'name',
        'amount_invested',
        'quantity',
        'purchase_price',
        'current_price',
        'total_value',
        'profit_loss',
        'purchase_date',
        'status',
        'investment_fee',
        'description'
    ];

    /**
     * Get the user that owns the investment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the account associated with the investment.
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(InvestmentAccount::class, 'account_id')->withTrashed();
    }
}
