<?php

namespace App\Models\Invoication;

use App\Models\Transaction\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property string $id
 * @property string $user_id
 * @property string $invoice_number
 * @property string $virtual_account
 * @property string $reference_id
 * @property string $tenant_id
 * @property string $type
 * @property string $status
 * @property string $description
 * @property Carbon $valid_until
 * @property User $user
 * @property Transaction $transaction
 * */
class Invocation extends Model
{
    use HasUuids;

    protected $table = 'invocations';

    protected $fillable = [
        'user_id',
        'invoice_number',
        'virtual_account',
        'reference_id',
        'tenant_id',
        'type',
        'valid_until',
        'description',
        'status',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return HasOne
     */
    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class, 'invocation_id');
    }
}
