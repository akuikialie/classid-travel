<?php

namespace App\Services\EWallet;

use App\Services\EWallet\Entity\WalletUser;
use Exception;
use Illuminate\Support\Facades\Cache;

trait WalletAccount
{
    /**
     * @param string $username
     * @param string $password
     *
     * @return bool
     * @throws \Exception
     */
    public function login(string $username, string $password): bool
    {
        $post = $this->client()->post('auth/oauth/login', [
            'school_key' => config('wallet.bcn'),
            'username' => $username,
            'password' => $password,
        ]);

        if ($post->ok()) {
            $body = $post->object();
            if ($body->user && $body->access_token) {
                $this->user = new WalletUser(
                    id: $body->user?->id ?? null,
                    va: config('wallet.bcn') . str_pad($body->user?->virtual_account ?? '', 10, '0', STR_PAD_LEFT),
                    name: $body->user?->name ?? '',
                    token: $body->user?->access_token ?? '',
                    isAdmin: config('wallet.admin.username')!='' && $username == config('wallet.admin.username'),
                );
                return true;
            }
        }

        if ($post->clientError()) {
            throw new Exception($post->object()->error, $post->status());
            // dump($post->toException()->response->json());
        }

        return false;
    }

    /**
     * @return \App\Services\EWallet\Entity\WalletUser|null
     */
    public function admin(): ?WalletUser
    {
        return Cache::rememberForever('walletAdmin', function () {
            if ($this->login(config('wallet.admin.username'), config('wallet.admin.password'))) {
                return $this->user;
            }
            return null;
        });
    }

    /**
     * @param int         $id
     * @param string      $va
     * @param string      $name
     * @param string|null $email
     *
     * @return \App\Services\EWallet\Entity\WalletUser|null
     * @throws \Exception
     */
    public function createUser(int $id, string $va, string $name, ?string $email = null): ?WalletUser
    {
        if (!$this->user->isAdmin()) {
            throw new Exception('not authorized', 403);
        }

        $username = $va;
        $password = "{$id}@{$va}";
        $vaCode = (int) preg_replace('/^('. config('wallet.bcn') .')(\d+)/', '$2', $va);

        $post = $this->client()->post('api/user/create', [
            // 'username' => config('wallet.bcn'),
            'username' => $username,
            'email' => $email,
            'name' => $name,
            'password' => $password,
            'password_confirmation' => $password,
            'pin' => '123456',
            'va_code' => $vaCode,
        ]);

        if ($post->successful()) {
            // dump($post->object());

            $user = $post->object()->data;
            if ($user) {
                return new WalletUser(
                    id: $user?->id ?? null,
                    va: $user?->virtual_account ?? '',
                    name: $user?->name ?? '',
                );
            }
        }

        if ($post->clientError()) {
            throw new Exception($post->object()->error, $post->status());
            // dump($post->toException()->response->json());
        }

        return null;
    }
}
