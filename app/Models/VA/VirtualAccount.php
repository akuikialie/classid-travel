<?php

namespace App\Models\VA;

use App\Concerns\Mutable;
use App\Contracts\MutableInterface;
use App\Models\Plan\PlanPackage;
use App\Models\Tenant\Tenant;
use App\Traits\HasTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Veelasky\LaravelHashId\Eloquent\HashableId;

/**
 * @property string $id
 * @property string $tenant_id
 * @property string $va_number
 * @property string $va_label
 * @property string $name
 * @property string $email
 * @property string $password
 * @property float $balance
 * @property float $usd_balance
 * @property Tenant $tenant
 * @property Collection<VirtualAccountMutation> $virtualAccountMutations
 * */
class VirtualAccount extends Model implements MutableInterface
{
    use HasFactory, HashableId, SoftDeletes;
    use Mutable;
    use HasTenant;

    const string MORPH_ALIAS = 'virtual_account';
    protected bool $shouldHashPersist = false;

    protected $table = 'virtual_accounts';
    protected $fillable = [
        'tenant_id',
        'va_number',
        'va_label',
        'name',
        'email',
        'password',
        'balance',
        'usd_balance',
    ];

    protected $casts = [
        'balance' => 'double',
    ];

    public function vaable(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'model_type', 'model_id');
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class,'tenant_id');
    }

    /**
     * Get the user that owns the VirtualAccount
     *
     * @return BelongsTo
     */
    public function myPackage(): BelongsTo
    {
        return $this->belongsTo(PlanPackage::class, 'package_id');
    }

    /**
     * Get the user that owns the VirtualAccount
     *
     * @return HasMany
     */
    public function virtualAccountMutations(): HasMany
    {
        return $this->hasMany(VirtualAccountMutation::class, 'virtual_account_id');
    }

    public function getMutableName(): string
    {
        return self::MORPH_ALIAS;
    }

    public function freshLock(array|string $with = []): mixed
    {
        return $this->newQueryWithoutScopes()
            ->with($with)
            ->where($this->getKeyName(), $this->getKey())
            ->lockForUpdate()
            ->first();
    }

    public function allowNegativeBalance(): bool
    {
        return false;
    }
}
