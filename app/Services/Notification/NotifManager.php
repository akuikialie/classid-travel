<?php

namespace App\Services\Notification;

use App\Models\Base\NotificationLog;
use Exception;
use LogicException;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Contracts\Container\BindingResolutionException;

class NotifManager
{
    /**
     * @var ?int
     */
    private ?int $tenantId = null;

    /**
     * @var string
     */
    private string $channel = '';

    /**
     * @var string
     */
    private string $sender = '';

    public function __construct(string $channel, ?int $tenantId = null, ?string $sender = null)
    {
        $this->channel = $channel;
        $this->tenantId = $tenantId;

        if (empty($sender) && !empty($channel) && $channel == 'woowa') {
            $this->sender = config('msnotif.woowa.eco.sender');
        }
    }

    /**
     * @param string $key
     * @param string $content
     * @param string $sender
     * @param string $recipient
     * @param bool   $isGroup
     * @param array  $attachements
     * @param string $type
     *
     * @return bool
     * @throws \Throwable
     */
    public function send(string $key, string $content, string $sender = '', string $recipient = '', bool $isGroup = false, array $attachements = [], string $type = 'system'): bool
    {
        $notifLog = null;

        if (!$this->getStatusActive($this->channel)) {
            app('notifLog')->error("send notif");
            app('notifLog')->error("code : 400");
            app('notifLog')->error("message : Notification service in-active");
            app('notifLog')->info("====================================================================================================\n");
            return false;
        }
        if (empty(trim($sender))) {
            $sender = $this->sender;
        }
        $prefMessage = '';
        if (in_array($this->channel, ['woowa', 'woowa_eco'])) {
            $recipient = preg_replace('/\s+/', '', $recipient);
            $prefMessage = $isGroup ? 'grup ' : 'nomor ';

            if (!$isGroup) {
                $recipient = preg_replace(['/^(0|\+?62|(?!0|\+?62|\+))(.*)$/', '/\++/'], ['62${2}', ''], $recipient);
            }
        }

        $processId = Str::uuid()->toString();
        $data = [
            "sender" => $sender, // nullable
            "recipient" => $recipient, // required, string
            "channel" => $this->channel, // required, string
            "content" => $content, // required, string
            "attachements" => $attachements,
        ];

        try {
            $logData = [
                'tenant_id' => $this->tenantId,
                'process_id' => $processId,
                'channel' => $this->channel,
                'source' => $key,
                'sender' => $sender,
                'receiver' => $recipient,
                'content' => $content,
                'type' => $type,
                'is_group' => $isGroup,
                'request_id' => app('request_id') ?? null,
            ];

            $notifLog = NotificationLog::create($logData);
        } catch (Exception $e) {
            throw_if(debugNonProduction(), $e);
            app('notifLog')->error("prepare notif");
            app('notifLog')->error("code : " . $e->getCode());
            app('notifLog')->error("message : " . $e->getMessage());
            app('notifLog')->error("traces : \n" . $e->getTraceAsString());
            app('notifLog')->info("====================================================================================================\n");
        }

        try {
            if (empty($sender) || empty($recipient)) {
                $respMessage = 'Sender tidak diketahui';
                if (empty($recipient)) {
                    $respMessage = $prefMessage . 'penerima tidak diketahui';
                }

                if ($notifLog instanceof NotificationLog) {
                    $notifLog->update([
                        'sent_status' => 'failed',
                        'resp_status' => $respMessage,
                    ]);
                }

                throw new Exception("failed: {$respMessage}". 400);
            }

            dispatch(new NotifSender($data['channel'], $processId, 'send', $data, $isGroup)); // ->onQueue('notification');

            return true;
        } catch (Exception $e) {
            throw_if(debugNonProduction(), $e);
            app('notifLog')->error("send notif");
            app('notifLog')->error("code : " . $e->getCode());
            app('notifLog')->error("message : " . $e->getMessage());
            app('notifLog')->error("traces : \n" . $e->getTraceAsString());
            app('notifLog')->info("====================================================================================================\n");
        }
        return false;
    }

    /**
     * @param string $operationId
     *
     * @return array
     * @throws LogicException
     * @throws BindingResolutionException
     */
    public function get(string $operationId = ''): array
    {
        $path = "";
        if (!empty(trim($operationId))) {
            $path = $operationId;
        }
        $baseUrl = preg_replace('/\/+$/', '', config('app.msnotif_url')) . '/api/notification/';
        $client = new Client([
            'base_uri' => $baseUrl,
            'verify' => false,
            'timeout' => 60,    // 1 menit
            'debug' => true
        ]);

        try {
            $response = $client->get($path);
            return json_decode($response->getBody()->getContents(), true) ?? [];

        } catch (Exception $e) {
            app('notifLog')->error("get info");
            app('notifLog')->error("code : " . $e->getCode());
            app('notifLog')->error("message : " . $e->getMessage());
            app('notifLog')->error("traces : \n" . $e->getTraceAsString());
            app('notifLog')->info("====================================================================================================\n");
        }

        return [];
    }

    public function getStatusActive(string $channel): bool
    {
        return true;
        // if (empty($this->schoolId)) return false;

        switch ($channel) {
            case 'email':
                $key = 'email';
                break;
            case 'fcm':
                $key = 'fcm';
                break;
            case 'sms':
                $key = 'sms';
                break;
            case 'telegram':
                $key = 'telegram';
                break;
            case 'whatsapp':
            case 'woowa':
            case 'woowa_eco':
                $key = 'whatsapp';
                break;
            default:
                return false;
        }

        $notifKeys = []; // explode(',', $notifKeys->value ?? '');
        return in_array($key, $notifKeys);
    }
}
