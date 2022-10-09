<?php

namespace App\Models\Referal;

use App\Models\Plan\PlanPackage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReferalLink extends Model
{
    use HasFactory;

    protected $table = 'referal_links';

    protected $fillable = [
        'summary', 'link', 'hash',
    ];

    /**
     * Get the user that owns the ReferalLink
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(PlanPackage::class, 'package_id');
    }

    /**
     * Get the user that owns the UserInvitation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
