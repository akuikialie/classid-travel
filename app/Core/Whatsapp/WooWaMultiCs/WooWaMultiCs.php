<?php

namespace App\Core\Whatsapp\WooWaMultiCs;

use CURLFile;
use Exception;
use Illuminate\Support\Arr;

class WooWaMultiCs
{
    protected string $baseUrl = 'https://multics.woo-wa.com/api/srv1';
    protected string $key;
    protected string $license;

    /**
     * @param string|array|null $options
     * @param string|null $license
     */
    public function __construct($options = null, ?string $license = null)
    {
        if (is_array($options)) {
            $this->key = !empty($options['key']) ? $options['key'] : '';
        } else {
            $this->key = !empty(trim($options)) ? trim($options) : '';
        }
        if (!empty($license)) {
            $this->license = $license;
        }
    }

    /**
     * @param string|null $key
     *
     * @return array
     */
    public function groupList(?string $key = null): array
    {
        $headers = [];
        if (!empty($key)) {
            $headers = ["device-key: {$key}"];
        };
        return $this->send('groups', 'GET', $headers, [CURLOPT_TIMEOUT => 360]);
    }

    /**
     * @param array $data
     * @param bool $group
     * @param string|null $key
     *
     * @item string $data['number']     whatsapp number or group number
     * @item string $data['message']    whatsapp message
     *
     * @return array
     */
    public function messageText(array $data, bool $group = false, ?string $key = null): array
    {
        $headers = ['Content-Type: application/x-www-form-urlencoded'];
        if (!empty($key)) {
            array_push($headers, "device-key: {$key}");
        }

        $params = ['number', 'message'];
        if (!Arr::has($data, $params)) {
            return ['status' => 400, 'message' => 'Invalid data params [required params is '. implode(', ', $params) .']'];
        }
        $postData = http_build_query(array_merge(Arr::only($data, $params), ['group' => (int) $group]), 1);

        return $this->send('send-text', 'POST', $headers, [
            CURLOPT_POSTFIELDS => $postData
        ]);
    }

    /**
     * @param array $data
     * @param bool $group
     * @param string|null $key
     *
     * @item string $data['number']     whatsapp number or group number
     * @item string $data['message']    whatsapp message
     * @item string $data['media']      raw content file media
     * @item string $data['type']       image|video|document
     *
     * @return array
     */
    public function messageMedia(array $data, bool $group = false, ?string $key = null): array
    {
        $headers = [];
        if (!empty($key)) {
            array_push($headers, "device-key: {$key}");
        }

        $params = ['number', 'message', 'media', 'type'];
        if (!Arr::has($data, $params)) {
            return ['status' => 400, 'message' => 'Invalid data params [required params is '. implode(', ', $params) .']'];
        }

        if (is_string($data['media'])) {
            $data['media'] = new CURLFile($data['media']);
        }

        $postData = array_merge(
            Arr::only($data, $params),
            ['group' => (int) $group]
        );

        return $this->send('send-media', 'POST', $headers, [
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POSTFIELDS => $postData
        ]);
    }

    /**
     * @param array $data
     * @param bool $group
     * @param string|null $key
     *
     * @item string $data['number']     whatsapp number or group number
     * @item string $data['message']    whatsapp message
     * @item string $data['media_url']  url based file media
     * @item string $data['type']       image|video|document
     *
     * @return array
     */
    public function messageMediaUrl(array $data, bool $group = false, ?string $key = null): array
    {
        $headers = ['Content-Type: application/x-www-form-urlencoded'];
        if (!empty($key)) {
            array_push($headers, "device-key: {$key}");
        }

        $params = ['number', 'message', 'media_url', 'type'];
        if (!Arr::has($data, $params)) {
            return ['status' => 400, 'message' => 'Invalid data params [required params is '. implode(', ', $params) .']'];
        }
        $postData = http_build_query(array_merge(
            Arr::only($data, $params),
            ['group' => (int) $group]
        ), 1);

        return $this->send('send-media-url', 'POST', $headers, [
            CURLOPT_POSTFIELDS => $postData
        ]);
    }

    /**
     * @return array
     */
    public function getQR(): array
    {
        return $this->send('generate-qr', 'GET', [], [CURLOPT_TIMEOUT => 360]);
    }

    /**
     * @param string|null $license
     *
     * @return array
     */
    public function deviceInfo(?string $license = null): array
    {
        if (empty($license)) $license = $this->license;
        $headers = ["license: {$license}"];

        return $this->send('device-info', 'GET', $headers, [CURLOPT_TIMEOUT => 360]);
    }

    /**
     * @param string|null $license
     *
     * @return array
     */
    public function deviceStatus(?string $key = null): array
    {
        $headers = [];
        if (!empty($key)) {
            $headers = ["device-key: {$key}"];
        }

        return $this->send('device-status', 'GET', $headers, [CURLOPT_TIMEOUT => 360]);
    }

    /**
     * @param string|null $license
     *
     * @return array
     */
    public function deviceLogout(?string $license = null): array
    {
        if (empty($license)) $license = $this->license;
        $headers = ["license: {$license}"];

        return $this->send('device-logout', 'GET', $headers, [CURLOPT_TIMEOUT => 360]);
    }

    /**
     * @param string|null $key
     *
     * @return array
     */
    public function deviceReLogin(?string $key = null): array   // a.k.a use-here
    {
        $headers = [];
        if (!empty($key)) {
            $headers = ["device-key: {$key}"];
        }
        return $this->send('relogin', 'GET', $headers, [CURLOPT_TIMEOUT => 360]);
    }

    /**
     * @param string $path
     * @param string $method
     * @param array $headers
     * @param array $options
     *
     * @return array
     * @throw \Throwable
     */
    private function send(string $path = '', string $method = 'GET', array $headers = [], array $options = []): array
    {
        try {
            $headerOpts = array_merge([
                "device-key: {$this->key}"
            ], $headers);
            unset($options[CURLOPT_HTTPHEADER]);

            $ch = curl_init("{$this->baseUrl}/{$path}");
            curl_setopt_array($ch, [
                CURLOPT_CUSTOMREQUEST => strtoupper($method),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_VERBOSE => 0,
                CURLOPT_CONNECTTIMEOUT => 0,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_HTTPHEADER => $headerOpts
            ] + $options);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($errCurl = curl_error($ch)) {
                return [
                    'status' => $httpCode,
                    'message' => $errCurl
                ];
            }

            curl_close($ch);

            return json_decode($response, true);

        } catch (Exception $e) {
            return [
                'status' => $e->getCode(),
                'message' => $e->getMessage()
            ];
        }
    }
}
