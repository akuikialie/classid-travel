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
    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->getWalletUrl();
    }

    /**
     * @return string
     */
    public function getWalletBCN(): string
    {
        return !empty($this->tenantCredentials['WALLET_BCN'])
            ? $this->tenantCredentials['WALLET_BCN']
            : config('wallet.bcn', '000000');
    }

    /**
     * @return string
     */
    public function getWalletUrl(): string
    {
        // return !empty($this->tenantCredentials['WALLET_URL'])
        //     ? $this->tenantCredentials['WALLET_URL']
        //     : config('wallet.url');
        return config('wallet.url') ?? $this->tenantCredentials?->WALLET_URL ?? config('wallet.fallback_url');
    }

    /**
     * @return string
     */
    public function getWalletAdminUser(): string
    {
        return !empty($this->tenantCredentials['WALLET_ADMIN_USER'])
            ? $this->tenantCredentials['WALLET_ADMIN_USER']
            : config('wallet.admin.username', '');
    }

    /**
     * @return string
     */
    public function getWalletAdminPwd(): string
    {
        return !empty($this->tenantCredentials['WALLET_ADMIN_PASS'])
            ? $this->tenantCredentials['WALLET_ADMIN_PASS']
            : config('wallet.admin.password', '');
    }

    /**
     * @return bool
     */
    public function ping(): bool
    {
        $get = $this->client()->get('api');

        $body = $get->json();

        if ($get->clientError()) {
            $body = $get->toException()->response->json();
        }

        return Arr::hasAny($body, ['error', 'data']);
    }

    /**
     * @return \Illuminate\Http\Client\PendingRequest
     */
    private function client(): PendingRequest
    {
        $stack = HandlerStack::create();
        $stack->push(Middleware::mapRequest(function (RequestInterface $request) {
            // $contentsRequest = (string) $request->getBody();
            return $request;
        }));

        $http = Http::init($this->getBaseUrl())
            ->withHeaders([
                'Accept' => 'application/json'
            ])
            ->withOptions([
                'verify' => config('wallet.secure'),
                'allow_redirects' => false,
                'timeout' => 180,
            ])
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
