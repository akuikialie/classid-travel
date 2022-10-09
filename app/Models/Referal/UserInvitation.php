<?php

namespace App\Models\Referal;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserInvitation extends Model
{
    use HasFactory;

    protected $table = 'user_invitations';

    /**
     * Get the user that owns the UserInvitation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the user that owns the UserInvitation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invitedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    /**
     * Get the user that owns the UserInvitation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function referalLink(): BelongsTo
    {
        return $this->belongsTo(ReferalLink::class, 'link_id');
    }


}
