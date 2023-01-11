<?php

namespace App\Core\Whatsapp\WooWa;

class WooWa
{
    // protected $baseUrl = 'http://116.203.92.59/api';
    protected $baseUrl = 'http://116.203.191.58/api';
    protected $key;
    protected $license;
    protected $validNumberOnly = true;

    public function __construct($options = null)
    {
        // $defaultKey = SchoolData::whereSchoolId(auth()->user()->school_id ?? 0)->whereKey('whatsapp_api_key')->first() ?? null;

        if (is_array($options)) {
            $this->key = !empty($options['key']) ? $options['key'] : '';
        } else {
            $this->key = !empty($options) ? $options : '';
        }

        /*if (app()->runningInConsole() && empty($this->key)) {
            $this->key = config('services.whatsapp.key');
        }*/

        if (empty($this->key)) {
            app('notifLog')->error('WooWa Eco - error connection.', [
                'code' => 500,
                'error' => 'unknown api-key',
            ]);
            return false;
        }
    }

    protected function doSend(string $url, array $data)
    {
        if (empty($data['key'])) {
            $data['key'] = $this->key;
        }

        if (!empty($data['phone_no'])) {
            $data['phone_no'] = preg_replace('/^(0|62)/', '+62', $data['phone_no']);

            if ($this->validNumberOnly) {
                $checked = $this->checkPhoneNumber($data['phone_no'], $data['key']);

                if (trim(strtolower($checked['status'])) != 'exists') {
                    app('notifLog')->error('WooWa Eco - Phone Number is not valid.', $checked);
                    throw new \Exception("Phone Number is not valid. #: {$data['phone_no']} is {$checked['status']}", 400);
                }
            }
        }
        $receiver = !empty($data['phone_no']) ? $data['phone_no'] :
                    (!empty($data['group_id']) ? $data['group_id'] :
                        (!empty($data['group_name']) ? $data['group_name'] : 'unknown')
                    );
        $data = json_encode($data);

        // if (debugNonProduction()) {
        //     app('whatsappLog')->debug('WooWa local handler.', [
        //         'url' => "{$this->baseUrl}/{$url}",
        //         'content-length' => strlen($data),
        //         'content' => $data,
        //     ]);
        //     throw new \Exception("WooWa blocked on non production", 403);
        // }

        try {
            $curl = curl_init("{$this->baseUrl}/{$url}");
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_VERBOSE, 0);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 0);
            curl_setopt($curl, CURLOPT_TIMEOUT, 600);   // 10 min
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data),
            ]);

            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                if (curl_errno($curl) == CURLE_OPERATION_TIMEOUTED) {
                    if (config('app.debug', false)) {
                        app('notifLog')->warning('WooWa Eco - timeout response waiting.', [
                            'url' => "{$this->baseUrl}/{$url}",
                            'receiver' => $receiver,
                            'request' => $data,
                            'response' => $err,
                        ]);
                    }
                    return 'request timeout';
                }

                app('notifLog')->error('WooWa Eco - failed to sent.', [
                    'code' => $httpCode,
                    'error' => $err,
                    'url' => "{$this->baseUrl}/{$url}",
                    'receiver' => $receiver,
                    'request' => $data,
                    'response' => $response,
                ]);
                throw new \Exception("cURL Error #:" . $err, 1);
            } else {
                if (config('app.debug', false)) {
                    app('notifLog')->info('WooWa Eco - successfully sent.', [
                        'url' => "{$this->baseUrl}/{$url}",
                        'receiver' => $receiver,
                        'request' => $data,
                        'response' => $response,
                    ]);
                }
                return (string) $response;
            }
        } catch (\Exception $e) {
            app('notifLog')->error('WooWa Eco - failed to sent.', [
                'code' => $e->getCode(),
                'error' => $e,
            ]);
            throw $e;
        }
    }

    public function checkNumber(string $phoneNumber = null, string $key = null)
    {
        return $this->checkPhoneNumber($phoneNumber, $key);
    }

    protected function checkPhoneNumber(string $phoneNumber = null, string $key = null)
    {
        $response = ['phone_no' => (string) $phoneNumber, 'status' => ''];

        if (empty($phoneNumber)) {
            $response['status'] = 'empty';
            return $response;
        }

        $response['status'] = 'exists';
        return $response;

        /*$data['key'] = $key ?? $this->key;
        $data['phone_no'] = preg_replace('/^(0|62)/', '+62', $phoneNumber);
        $data = json_encode($data);

        try {
            $curl = curl_init("{$this->baseUrl}/check_number");
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_VERBOSE, 0);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 0);
            curl_setopt($curl, CURLOPT_TIMEOUT, 360);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data),
            ]);

            $curlResponse = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                app('whatsappLog')->error('WooWa failed to sent.', [
                    'code' => $httpCode,
                    'error' => $err,
                ]);
                throw new \Exception("cURL Error #:" . $err, 1);
            } else {
                $response['status'] = (string) $curlResponse;
                return $response;
            }
        } catch (\Exception $e) {
            app('whatsappLog')->error('WooWa failed to sent.', [
                'code' => $e->getCode(),
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }*/
    }
}
