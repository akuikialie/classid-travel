<?php

declare(strict_types=1);

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
     * @throws Exception
     */
    public function login(string $username, string $password): bool
    {
        $walletUser = config('wallet.admin.username');

        if ($this->tenantCredentials){
            $walletUser = $this->tenantCredentials['WALLET_ADMIN_USER'];
        }
        if ($username == $walletUser) {
            return $this->_doLogin($username, $password);
        }

        $userLogin = Cache::remember("walletUser.$username", now()->addMonths(6), function () use ($username, $password) {
            if ($this->_doLogin($username, $password)) {
                return $this->user;
            }
            return null;
        });

        if (!$userLogin) {
            Cache::forget("walletUser.$username");
        }

        return $userLogin instanceof WalletUser;
    }

    /**
     * @return WalletUser|null
     */
    public function admin(): ?WalletUser
    {
        $username = config('wallet.admin.username');
        $password = config('wallet.admin.password');

        if ($this->tenantCredentials){
            $username = $this->tenantCredentials['WALLET_ADMIN_USER'];
            $password = $this->tenantCredentials['WALLET_ADMIN_PASS'];
        }

        $adminUser = Cache::rememberForever('walletAdmin', function () use ($username, $password) {
            if ($this->login(username: $username, password: $password)) {
                return $this->user;
            }
            return null;
        });

        if (is_null($adminUser)) {
            Cache::forget("walletAdmin");
        }

        if (is_null($this->user)){
            $this->user = $adminUser;
        }
        return $adminUser;
    }

    /**
     * @param string      $id
     * @param string      $va
     * @param string      $name
     * @param string|null $email
     * @return WalletUser|null
     * @throws Exception
     */
    public function createUser(string $id, string $va, string $name, ?string $email = null): ?WalletUser
    {
        dd($this->user);
        if (!$this->user->isAdmin()) {
            throw new Exception('not authorized', 403);
        }

        $username = $va;
        $password = "{$id}@{$va}";

        $getBcn = $this->tenantCredentials['WALLET_BCN'] ?? config('wallet.bcn');
        $vaCode = (int) preg_replace('/^('. $getBcn .')(\d+)/', '$2', $va);

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

        $getBody = json_decode($post->body(), true);

        if (isset($getBody['error'])){
            throw new Exception($getBody['error']);
        }

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
        return null;
    }

    private function _doLogin(string $username, string $password): bool
    {
        $credentials = $this->tenantCredentials;
        $post = $this->client()->post('auth/oauth/login', [
            'school_key' => $credentials['WALLET_BCN'] ?? config('wallet.bcn'),
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
                    token: $body->access_token,
                    isAdmin: config('wallet.admin.username')!='' && $username == config('wallet.admin.username'),
                );

                return true;
            }
        }

        return false;
    }
}
