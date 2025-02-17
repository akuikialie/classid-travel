<?php

namespace App\Models\Transaction;

use App\Models\Invoication\Invocation;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $tenant_id
 * @property string $invocation_id
 * @property string $amount
 * @property string $trx_method
 * @property string $trx_type
 * @property string $trx_date
 * @property User $user
 * @property Transaction $transaction
 * */
class Transaction extends Model
{
    use HasUuids;

    protected $table = 'transactions';

    protected $fillable = [
        'user_id',
        'tenant_id',
        'invocation_id',
        'amount',
        'trx_method',
        'trx_type',
        'trx_date',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return BelongsTo
     */
    public function invocation(): BelongsTo
    {
        return $this->belongsTo(Invocation::class, 'invocation_id');
    }
}
