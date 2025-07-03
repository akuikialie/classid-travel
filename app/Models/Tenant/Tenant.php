<?php

namespace App\Models\Tenant;

use App\Concerns\Numberable;
use App\Contracts\NumberableInterface;
use App\Jobs\GenerateDefaultRecordForCreatedTenant;
use App\Models\Master\Address;
use App\Models\Master\Email;
use App\Models\Master\Phone;
use App\Traits\HasTenant;
use App\Models\Jamaah\Jamaah;
use App\Models\Plan\PlanPackage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Veelasky\LaravelHashId\Eloquent\HashableId;

/**
 * @property string $id
 * @property string $tenant_id
 * @property string $name
 * @property string $slug
 * @property string $app_domain
 * @property string $bcn
 * @property string $wallet_login
 * @property bool $is_active
 * @property float $fee_admin
 * @property array $options
 * */

class Tenant extends Model implements HasMedia, NumberableInterface
{
    use SoftDeletes, HashableId, InteractsWithMedia, HasTenant;
    use Numberable;

    const string MORPH_ALIAS = 'tenant';

    protected static function booted(): void
    {
        static::created(function (Tenant $tenant) {
            dispatch_sync(new GenerateDefaultRecordForCreatedTenant($tenant));
        });
    }

    protected bool $shouldHashPersist = false;

    protected $table = 'tenants';

    protected $fillable = [
        'name',
        'slug',
        'app_domain',
        'bcn',
        'wallet_login',
        'is_active',
        'fee_admin',
        'options',
    ];

    protected $casts = [
        'wallet_login' => 'array',
        'fee_admin' => 'float',
        'options' => 'array',
    ];

    public function addresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable', 'model_type', 'modal_id');
    }

    public function emails(): MorphMany
    {
        return $this->morphMany(Email::class, 'addressable', 'model_type', 'modal_id');
    }

    public function phone(): MorphMany
    {
        return $this->morphMany(Phone::class, 'addressable', 'model_type', 'modal_id');
    }

    public function jamaah(): HasMany
    {
        return $this->hasMany(Jamaah::class, 'tenant_id');
    }

    public function packages(): HasMany
    {
        return $this->hasMany(PlanPackage::class, 'tenant_id');
    }

    public function tenantData(): HasMany
    {
        return $this->hasMany(TenantData::class, 'tenant_id');
    }

    public function getNumberableName(): string
    {
        return self::MORPH_ALIAS;
    }
}
