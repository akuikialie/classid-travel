<?php

namespace App\Models\Mutation;

use App\Concerns\Mutable;
use App\Models\Tenant\Tenant;
use App\Models\Transaction\Transaction;
use App\Traits\HasTenant;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Model Mutation
 *
 * @property string $id
 * @property string $mutable_type
 * @property string $mutable_id
 * @property string $type
 * @property string $info
 * @property double $amount
 * @property double $amount_before
 * @property double $amount_after
 * @property double $fee_admin
 * @property string $transaction_id
 * @property string $user_id
 * @property string $tenant_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Mutable $mutable
 * @property Transaction $transaction
 * */
class Mutation extends Model
{
    use HasFactory, HasUuids;
    use HasTenant;

    protected $table = 'mutations';

    protected $fillable = [
        'mutable_type',
        'mutable_id',
        'type',
        'info',
        'amount',
        'amount_before',
        'amount_after',
        'transaction_id',
        'user_id',
        'tenant_id',
        'fee_admin'
    ];

    protected $casts = [
        'amount' => 'double',
        'amount_before' => 'double',
        'amount_after' => 'double',
        'fee_admin' => 'double'
    ];

    /**
     * Define 'morphTo' relation with other mutable class.
     *
     * @return MorphTo
     */
    public function mutable(): MorphTo
    {
        return $this->morphTo('mutable');
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function getDateFormat(): string
    {
        return 'Y-m-d H:i:s.u';
    }

}
