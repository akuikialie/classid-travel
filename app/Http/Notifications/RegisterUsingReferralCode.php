<?php

namespace App\Http\Notifications;

use Exception;
use App\Core\Bus\AbstractNotification;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegisterUsingReferralCode extends AbstractNotification implements ShouldQueue
{
    /**
     * @var array|string[]
     */
    protected array $tags = ['whatsapp'];

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, private readonly string|null $message = null)
    {
        parent::__construct($user);
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Throwable
     */
    public function handle(): void
    {
        try {
            $user = (object)$this->userData();

            // Begin Queue Notifications
            $this->toWoowa($user);

        } catch (Exception $e) {
            // throw_if(debugNonProduction(), $e);
            // app('notifLog')->error("invoice student");
            app('notifLog')->error("source : " . __CLASS__);
            app('notifLog')->error("code : " . $e->getCode());
            app('notifLog')->error("message : " . $e->getMessage());
            // app('notifLog')->error("data : " . json_encode($transaction));
            app('notifLog')->error("traces : \n" . $e->getTraceAsString());
            app('notifLog')->info("====================================================================================================\n");
        }
    }

    /**
     * content for whatsApp.
     *
     * @param object $user
     *
     * @return void
     * @throws \Throwable
     */
    private function toWoowa(object $user): void
    {
        $woowa = msnotif($user->woowa_channel);
        $woowa->send('msnotif.test', $this->message, $user->woowa_key, $user->phone);
    }


    /**
     * Determine the time at which the job should timeout.
     *
     * @return \DateTime
     */
    public function retryUntil(): \DateTime
    {
        return now()->addSeconds(10);
    }
}
