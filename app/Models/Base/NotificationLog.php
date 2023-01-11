<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Notification Log model.
 *
 * @author      yusron arif <yusron.arif4@gmail.com>
 */
class NotificationLog extends Model
{
    use SoftDeletes, HasUuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notification_logs';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tenant_id', 'process_id', 'channel', 'source',
        'sender', 'receiver', 'content',
        'sent_status', 'resp_status', 'resp_id', 'channel_status',
        'type', 'requested_at', 'is_group',
        'request_id', 'sender_request_id', 'raw_response',
    ];

    protected $casts = [
        'raw_response' => 'array',
    ];

    public function sentStatus($value): Attribute
    {
        return Attribute::make(
            set: fn($value) => substr($value, 0, 250),
        );
        // $this->attributes['sent_status'] = substr($value, 0, 250);
    }

    public function respStatus($value): Attribute
    {
        return Attribute::make(
            set: fn($value) => substr($value, 0, 250),
        );
        // $this->attributes['resp_status'] = substr($value, 0, 250);
    }

    public function channelStatus($value): Attribute
    {
        return Attribute::make(
            set: fn($value) => substr($value, 0, 250),
        );
        // $this->attributes['channel_status'] = substr($value, 0, 250);
    }
}
