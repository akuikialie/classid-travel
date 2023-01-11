<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Jamaah\Jamaah;
use App\Models\Referral\UserInvitation;
use App\Models\Tenant\Tenant;
use App\Models\VA\VirtualAccount;
use App\Traits\HasTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    use HashableId, InteractsWithMedia, HasTenant;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'name', 'username', 'phone', 'password',
        'is_super',
        'locale', 'timezone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return HasOne
     */
    public function jamaah(): HasOne
    {
        return $this->hasOne(Jamaah::class, 'user_id');
    }

    /**
     * @return MorphOne
     */
    public function tabungan(): MorphOne
    {
        return $this->morphOne(VirtualAccount::class, 'vaable', 'model_type', 'model_id');
    }

    /**
     * Get all the comments for the User
     *
     * @return HasMany
     */
    public function peopleInvites(): HasMany
    {
        return $this->hasMany(UserInvitation::class, 'invited_by');
    }
}
