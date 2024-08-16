<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;


    protected $fillable = [
        'account_id',
        'type',
        'amount',
        'currency',
        'recipient_sender_account_id',
        'description',
        'transaction_date',
        'status',
        'transaction_fee',
        'user_id',
        'recipient_account_type',
        'recipient_sender_account_number'
    ];

    protected $with = ['account'];

    /**
     * Get the account that owns the transaction.
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(TransactionAccount::class, 'account_id')->withTrashed();
    }

    /**
     * Get the recipient account for the transaction.
     */
    public function recipientAccount(): BelongsTo
    {
        return $this->belongsTo(TransactionAccount::class, 'recipient_account_id', 'id')
            ->withTrashed();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
