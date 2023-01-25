<?php

declare(strict_types=1);

namespace App\Services\EWallet;

use App\Core\Bus\Http;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Illuminate\Container\Container;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Psr\Http\Message\RequestInterface;

trait WalletBase
{
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function ping(): bool
    {
        $get = $this->client()->get('api');

        $body = $get->json();

        if ($get->clientError()) {
            $body = $get->toException()->response->json();
        }

        return Arr::hasAny($body, ['error', 'data']);
    }

    private function client(): PendingRequest
    {
        $stack = HandlerStack::create();
        $stack->push(Middleware::mapRequest(function (RequestInterface $request) {
            // $contentsRequest = (string) $request->getBody();
            return $request;
        }));

        $http = Http::init($this->baseUrl)
            ->acceptJson()
            ->timeout(180)
            ->withoutVerifying()
            ->withoutRedirecting()
            ->setHandler($stack);

        $token = $this->user?->token ?? null;
        if ($token) {
            $http->withToken($token);
        }

        return $http;
    }

    /**
     * Convert json to errors validation
     *
     * @param array $body
     * @param bool  $object
     *
     * @return array|object
     */
    private function toPaginate(array $body, bool $object = true): array|object
    {
        $pageName = 'page';
        $currentPage = empty($body['meta']['current_page']) ? 1 : $body['meta']['current_page'];
        $page = $currentPage ?? Paginator::resolveCurrentPage($pageName);
        $perPage = empty($body['meta']['per_page']) ? 15 : $body['meta']['per_page'];
        $data = $body['data'] ?? [];
        $total = empty($body['meta']['total']) ? count((array) $data) : $body['meta']['total'];
        $results = $object ? json_decode(json_encode($data)) : $data;

        return $this->paginator($results, $total, $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => $pageName,
        ]);
    }

    private function paginator($items, $total, $perPage, $currentPage, $options)
    {
        return Container::getInstance()->makeWith(LengthAwarePaginator::class, compact(
            'items', 'total', 'perPage', 'currentPage', 'options'
        ));
    }
}
