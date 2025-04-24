<?php

namespace App\Models\VA;

use App\Models\User;
use App\Traits\HasTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $tenant_id
 * @property string $virtual_account_id
 * @property float $amount_before
 * @property float $amount
 * @property float $amount_after
 * @property float $currency_exchange_rate
 * @property float $usd_amount_before
 * @property float $usd_amount
 * @property float $usd_amount_after
 * */
class VirtualAccountMutation extends Model
{
    use HasUuids;
    use HasFactory;
    use HasTenant;

    protected bool $shouldHashPersist = true;

    protected $table = 'virtual_account_mutations';
    protected $fillable = [
        'actor_id',
        'tenant_id',
        'virtual_account_id',
        'amount_before',
        'amount',
        'amount_after',
        'currency_exchange_rate',
        'usd_amount_before',
        'usd_amount',
        'usd_amount_after',
    ];

    protected $casts = [
        'amount_before' => 'double',
        'amount' => 'double',
        'amount_after' => 'double',
        'currency_exchange_rate' => 'double',
        'usd_amount_before' => 'double',
        'usd_amount' => 'double',
        'usd_amount_after' => 'double',
    ];

    public function performer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function virtualAccount(): BelongsTo
    {
        return $this->belongsTo(VirtualAccount::class, 'virtual_account_id');
    }
}
