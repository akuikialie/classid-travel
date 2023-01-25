<?php

namespace App\Services\Notification;

use Exception;
use App\Models\Base\NotificationLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use LogicException;

class NotifSender implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    private array $body = [];

    /**
     * Create a new job instance.
     *
     * @param string $channel
     * @param string $processId
     * @param string $path
     * @param array  $params
     * @param bool   $isGroup
     */
    public function __construct(
        protected string $channel,
        protected string $processId,
        protected string $path,
        protected array $params,
        protected bool $isGroup = false
    )
    {}

    /**
     * Execute the job.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function handle(): void
    {
        try {
            if (!in_array(app()->environment(), ['prod', 'production', 'demo'])) {
                throw new Exception("your environment is set as ". app()->environment() .", this feature only active for production, and demo". 400);
            }

            $baseUrl = preg_replace('/\/+$/', '', config('app.msnotif_url')) . '/api/notification/';

            if ($this->channel === 'woowa_eco') {
                $response = $this->woowaEcoSender($this->params, $this->isGroup);
                $ecoBody = $response->body();
                $respBody = [
                    'rc' => strtolower($ecoBody) ?? 'unknown',
                    'data' => [
                        'id' => 'eco-'. Str::uuid()->toString(),
                        'created_at' => now()
                    ]
                ];
            } else {
                $client = Http::baseUrl($baseUrl)->withoutVerifying()->withoutRedirecting()->timeout(180);

                $response = $client->post('send', $this->params);
                $respBody = $response->json();
            }
            $this->body = $respBody;

            $this->updateLog($this->processId, 'successfully', [
                'sent_status' => empty($respBody) ? 'rto' : 'ok',
                'resp_status' => strtolower($respBody['rc'] ?? 'unknown'),
                'resp_id' => (string) $respBody['data']['id'],
                'requested_at' => (string) $respBody['data']['created_at'],
                'raw_response' => json_encode((array) $respBody),
            ]);

        // } catch (RequestException $e) {
        //     // $respCode = $e->getCode();

        //     $req = $e->getRequest();
        //     app('notifLog')->error("error http request");
        //     app('notifLog')->error("request : \n", [
        //         'uri' => $req->getUri(),
        //         'target' => $req->getRequestTarget(),
        //         'headers' => $req->getHeaders(),
        //         'body' => $req->getBody(),
        //     ]);
        //     app('notifLog')->error("code : " . $e->getCode());
        //     app('notifLog')->error("message : " . $e->getMessage());
        //     app('notifLog')->error("traces : \n" . $e->getTraceAsString());
        //     app('notifLog')->info("====================================================================================================\n");

        //     $this->updateLog($this->processId, 'error http request', [
        //         'raw_response' => json_encode([
        //             'request_uri' => $req->getUri(),
        //             'request_target' => $req->getRequestTarget(),
        //             'request_headers' => $req->getHeaders(),
        //             'request_body' => $req->getBody(),
        //             'code' => $e->getCode(),
        //             'message' => $e->getMessage(),
        //             'traces' => $e->getTraceAsString(),
        //         ]),
        //     ], $e);

        } catch (Exception $e) {
            // $respCode = $e->getCode();

            app('notifLog')->error("error send notif");
            app('notifLog')->error("code : " . $e->getCode());
            app('notifLog')->error("message : " . $e->getMessage());
            app('notifLog')->error("traces : \n" . $e->getTraceAsString());
            app('notifLog')->info("====================================================================================================\n");

            $this->updateLog($this->processId, 'error send notif', [
                'raw_response' => json_encode([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                    'traces' => $e->getTraceAsString(),
                ]),
            ], $e);
        }
    }

    /**
     * The job failed to process.
     *
     * @param Exception $exception
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function failed(Exception $exception): void
    {
        // Send user notification of failure, etc...
        $this->updateLog($this->processId, 'failed job send notif', [], $exception);
    }

    protected function woowaEcoSender(array $params, bool $isGroup = false): Response
    {
        $baseUrl = 'http://116.203.191.58/api/';

        $client = Http::baseUrl($baseUrl)->withoutVerifying()->withoutRedirecting()->timeout(180);

        if ($isGroup) {
            $path = 'send_message_group_id';
            $data = [
                'key' => $params['sender'] ?? '',
                'group_id' => $params['recipient'] ?? '',
                'message' => $params['content'] ?? ''
            ];
        } else {
            $path = 'send_message';
            $data = [
                'key' => $params['sender'] ?? '',
                'phone_no' => $params['recipient'] ?? '',
                'message' => $params['content'] ?? ''
            ];
        }

        return $client->post($path, $data);
    }

    /**
     * @param string $id
     * @param string $moment
     * @param array $data
     * @param null|Exception $exception
     *
     * @return void
     * @throws LogicException
     * @throws BindingResolutionException
     */
    protected function updateLog(string $id, string $moment = 'successfully', array $data = [], ?Exception $exception = null): void
    {
        try {
            if (empty($data['resp_status'])) {
                $data['resp_status'] = $moment;
            }
            $data['sender_request_id'] = app('request_id');

            if ($exception instanceof Exception) {
                $data['sent_status'] = 'failed';
                $data['resp_status'] = "[{$moment}] " . $exception->getCode() . ": " . $exception->getMessage();
            }
            NotificationLog::where('process_id', $id)->update($data);

        } catch (Exception $ex) {
            // throw_if(debugNonProduction(), $ex);
            app('notifLog')->error("{$moment} - update logs");
            app('notifLog')->error("process-id : " . $id);
            app('notifLog')->error("code : " . $ex->getCode());
            app('notifLog')->error("message : " . $ex->getMessage());
            app('notifLog')->error("traces : \n" . $ex->getTraceAsString());
            app('notifLog')->info("====================================================================================================\n");
        }
    }
}
