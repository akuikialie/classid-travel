<?php

namespace App\Services\EWallet;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Psr\Http\Message\RequestInterface;

trait HasWallet
{
    public function login(string $username, string $password): array
    {
        $post = $this->client()->post('auth/oauth/login', [
            'school_key' => env('WALLET_BCN', ''),
            'username' => $username,
            'password' => $password,
        ]);

        if ($post->ok()) {
            $body = $post->object();
            $this->user['va'] = env('WALLET_BCN', '') . str_pad($body->user?->virtual_account ?? '', 10, '0', STR_PAD_LEFT);
            $this->user['name'] = $body->user?->name ?? '';
            $this->user['token'] = $body->access_token ?? '';
        }

        if ($post->clientError()) {
            // dump($post->toException()->response->json());
        }

        return $this->user;
    }

    public function admin(): array
    {
        return Cache::rememberForever('walletAdmin', function () {
            return $this->login(env('WALLET_ADMIN_USER', ''), env('WALLET_ADMIN_PASS', ''));
        });
    }
}