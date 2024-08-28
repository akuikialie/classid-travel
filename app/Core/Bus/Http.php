<?php

namespace App\Core\Bus;

use Exception;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;

class Http extends PendingRequest
{
    public function __construct(?Factory $factory = null, string|null $baseUrl = null)
    {
        parent::__construct($factory);
        if ($baseUrl) {
            $this->baseUrl =$baseUrl;
        }
    }

    public static function init(string|null $baseUrl = null): static
    {
        return new static(baseUrl: $baseUrl);
    }

    /**
     * Issue a GET request to the given URL.
     *
     * @param string $url
     * @param null $query
     *
     * @return \Illuminate\Http\Client\Response
     * @throws \Exception
     */
    public function get(string $url, $query = null): Response
    {
        return parent::get($url, $query)
            ->onError(fn (Response $response) => $this->setLog($response, 'GET', $url, query: $query));
    }

    /**
     * Issue a HEAD request to the given URL.
     *
     * @param  string  $url
     * @param  array|string|null  $query
     * @return \Illuminate\Http\Client\Response
     */
    public function head(string $url, $query = null): Response
    {
        return parent::head($url, $query)
            ->onError(fn (Response $response) => $this->setLog($response, 'HEAD', $url, query: $query));
    }

    /**
     * Issue a POST request to the given URL.
     *
     * @param  string  $url
     * @param  array  $data
     * @return \Illuminate\Http\Client\Response
     */
    public function post(string $url, $data = []): Response
    {
        return parent::post($url, $data)
            ->onError(fn (Response $response) => $this->setLog($response, 'POST', $url, data: $data));
    }

    /**
     * Issue a PATCH request to the given URL.
     *
     * @param  string  $url
     * @param  array  $data
     * @return \Illuminate\Http\Client\Response
     */
    public function patch($url, $data = []): Response
    {
        return parent::patch($url, $data)
            ->onError(fn (Response $response) => $this->setLog($response, 'PATCH', $url, data: $data));
    }

    /**
     * Issue a PUT request to the given URL.
     *
     * @param  string  $url
     * @param  array  $data
     * @return \Illuminate\Http\Client\Response
     */
    public function put($url, $data = []): Response
    {
        return parent::put($url, $data)
            ->onError(fn (Response $response) => $this->setLog($response, 'PUT', $url, data: $data));
    }

    /**
     * Issue a DELETE request to the given URL.
     *
     * @param string $url
     * @param array $data
     *
     * @return \Illuminate\Http\Client\Response
     * @throws \Exception
     */
    public function delete($url, $data = []): Response
    {
        return parent::delete($url, $data)
            ->onError(fn (Response $response) => $this->setLog($response, 'DELETE', $url, data: $data));
    }

    private function setLog(Response $response, string $method, string $url, string|null $query = null, array $data = []): void
    {
        $exception = $response->toException();
        if ($exception instanceof RequestException) {
            $headers = $exception->response->headers();
            $body = $response->json();

            try {
                $uriPath = $response->effectiveUri()?->__toString() ?? $url;
            } catch (Exception $e) {
                $uriPath = $url;
            }

            $logData = [
                'method' => $method,
                'url' => $uriPath,
                'query' => $query,
                'data' => $data,
                'headers' => $headers,
                'body' => $body,
            ];
            logError($exception, title: "[{$logData['method']}] Request Http Error", data: $logData);
        }
    }
}
