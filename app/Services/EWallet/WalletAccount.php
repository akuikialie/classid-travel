<?php
declare(strict_types=1);

namespace App\Services\EWallet;

use App\Services\EWallet\Entity\WalletUser;
use Exception;

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
        $walletBCN = $this->getWalletBCN();
        $walletUser = $this->getWalletAdminUser();

        if ($username == $walletUser) {
            return $this->_doLogin($username, $password);
        }

        $userLogin = app('cache')->remember("walletUser:$walletBCN:$username", now()->addMonths(6), function () use ($username, $password) {
            if ($this->_doLogin($username, $password)) {
                return $this->user;
            }
            return null;
        });

        if (!$userLogin) {
            app('cache')->forget("walletUser:$walletBCN:$username");
        }

        return $userLogin instanceof WalletUser;
    }

    /**
     * @return WalletUser|null
     * @throws \Exception
     */
    public function admin(): ?WalletUser
    {
        $walletBCN = $this->getWalletBCN();
        $username = $this->getWalletAdminUser();
        $password = $this->getWalletAdminPwd();

        $adminUser = app('cache')->rememberForever("walletAdmin:$walletBCN", function () use ($username, $password) {
            if ($this->login(username: $username, password: $password)) {
                return $this->user;
            }
            return null;
        });

        if (is_null($adminUser)) {
            app('cache')->forget("walletAdmin:$walletBCN");
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
    public function createUser(string $id, string $va, string $name, string|null $email = null): ?WalletUser
    {
        if (!$this->user->isAdmin()) {
            throw new Exception('not authorized', 403);
        }

        $username = $va;
        $password = "{$id}@{$va}";

        $walletBCN = $this->getWalletBCN();
        $vaCode = (int) preg_replace('/^('. $walletBCN .')(\d+)/', '$2', $va);

        // dump([
        //     // 'username' => config('wallet.bcn'),
        //     'username' => $username,
        //     'email' => $email,
        //     'name' => $name,
        //     'password' => $password,
        //     'password_confirmation' => $password,
        //     'pin' => '123456',
        //     'va_code' => $vaCode,
        // ]);

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
        // dump($getBody);

        if (isset($getBody['error'])){
            dump($getBody);
            throw new Exception($getBody['error']);
        }

        if ($post->successful()) {
            // dump($post->object());
            $user = $post->object()->data;
            // dump($user);
            if ($user) {
                return new WalletUser(
                    id: $user?->id ?? null,
                    bcn: $walletBCN,
                    va: $user?->virtual_account ?? '',
                    name: $user?->name ?? '',
                );
            }
        }
        // exit();
        return null;
    }

    private function _doLogin(string $username, string $password): bool
    {
        $walletBCN = $this->getWalletBCN();
        $walletAdmin = $this->getWalletAdminUser();

        $post = $this->client()->post('auth/oauth/login', [
            'school_key' => $walletBCN,
            'username' => $username,
            'password' => $password,
        ]);

        if ($post->ok()) {
            $body = $post->object();
            if ($body->user && $body->access_token) {
                $this->user = new WalletUser(
                    id: $body->user?->id ?? null,
                    bcn: $walletBCN,
                    va: $walletBCN . str_pad($body->user?->virtual_account ?? '', 10, '0', STR_PAD_LEFT),
                    name: $body->user?->name ?? '',
                    token: $body->access_token,
                    isAdmin: $walletAdmin == $username,
                );

                return true;
            }
        }

        return false;
    }
}
