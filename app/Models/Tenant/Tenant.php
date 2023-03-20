<?php

namespace App\Models\Tenant;

use App\Models\HashableId;
use App\Models\Master\Address;
use App\Models\Master\Email;
use App\Models\Master\Phone;
use App\Traits\HasTenant;
use App\Models\Jamaah\Jamaah;
use App\Models\Plan\PlanPackage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Tenant extends Model implements HasMedia
{
    use SoftDeletes, HashableId, InteractsWithMedia, HasTenant;

    protected $fillable = [
        'name', 'slug', 'app_domain', 'BCN', 'wallet_login', 'is_active'
    ];

    protected $casts = [
        'wallet_login' => 'array'
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
}
