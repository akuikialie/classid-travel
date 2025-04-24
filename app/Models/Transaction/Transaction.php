<?php

namespace App\Models\Transaction;

use App\Models\Invoication\Invocation;
use App\Models\Mutation\Mutation;
use App\Models\User;
use App\Traits\HasTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property string $id
 * @property string $tenant_id
 * @property string $invocation_id
 * @property string $amount
 * @property string $trx_method
 * @property string $trx_number
 * @property string $trx_type
 * @property string $trx_date
 * @property User $user
 * @property Transaction $transaction
 * @property Collection<Mutation> $mutations
 * */
class Transaction extends Model
{
    use HasUuids;
    use HasTenant;

    protected $table = 'transactions';

    protected $fillable = [
        'user_id',
        'tenant_id',
        'invocation_id',
        'amount',
        'trx_number',
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

    public function mutations(): HasMany
    {
        return $this->hasMany(Mutation::class, 'transaction_id');
    }
}
