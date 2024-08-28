<?php

namespace App\Models\Referral;

use App\Models\Plan\PlanPackage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Veelasky\LaravelHashId\Eloquent\HashableId;

class ReferralLink extends Model
{
    use HasFactory, SoftDeletes, HashableId;

    protected bool $shouldHashPersist = true;

    protected $table = 'referral_links';

    protected $fillable = [
        'summary', 'link', 'hash', 'tenant_id',
        'package_id', 'created_by',
    ];

    /**
     * Get the user that owns the ReferralLink
     *
     * @return BelongsTo
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(PlanPackage::class, 'package_id');
    }

    /**
     * Get the user that owns the UserInvitation
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
