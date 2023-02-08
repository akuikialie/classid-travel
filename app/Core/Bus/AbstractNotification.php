<?php

namespace App\Core\Bus;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Notification abstract.
 *
 * @author    yusron arif <yusron.arif4@gmail.com>
 */
abstract class AbstractNotification
{
    use Dispatchable, Queueable, InteractsWithQueue;

    /**
     * @var User
     */
    protected User $user;

    /**
     * @var array|string[]
     */
    protected array $tags = [];

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user)
    {
        $this->queue = 'notification';
        // $this->queue = 'priority';
        $this->user = $user;
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array
     */
    public function tags(): array
    {
        return array_merge(['notification'], $this->tags);
    }

    protected function userData(): array
    {
        $user = $this->user->only(['id', 'tenant_id', 'name', 'username', 'phone', 'timezone', 'locale']);
        if (!$user['phone']) {
            $user['phone'] = '';
        }
        $user['tenant_name'] = $this->user->tenant?->name ?? '';
        $user['woowa_channel'] = 'woowa_eco';
        $user['woowa_key'] = config('msnotif.woowa.eco.sender');
        // $user['woowa_group'] = $this->user->schoolData('woowa_group', '');

        return $user;
    }
}
