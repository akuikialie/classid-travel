<?php
declare(strict_types=1);

namespace App\Services\EWallet;

use App\Services\EWallet\Entity\WalletUser;
use Exception;

class WalletService implements Contract\WalletService
{
    use WalletBase;
    use WalletAccount;

    private ?array $tenantCredentials = null;
    private ?WalletUser $user = null;

    public function __construct(private readonly ?string $token = null)
    {
        $this->tenantCredentials = activeTenant()?->wallet_login ?? [];
    }

    /**
     * @return array|object
     * @throws Exception
     */
    public function transactionList(): array|object
    {
        // $http = $this->client()->get('api/admin/finance/transaction', [
        //     'user_id' => (array) $this->user->id,
        // ]);
        $http = $this->client()->get('api/student/finance/transaction');

        $list = [];
        if ($http->successful()) {
            $list = $http->json();
        }

        return $this->toPaginate($list);
    }


    /**
     * @return array|object
     * @throws Exception
     */
    public function getUser(): array|object
    {
        $http = $this->client()->get('api/user');

        $getBody = json_decode($http->body(), true);

        if (isset($getBody['error'])){
            throw new Exception($getBody['error']);
        }

        if ($http) {
            return $http->json()['data'];
        }

        return [];
    }

    /**
     * @param int         $amount
     * @param string|null $description
     *
     * @return array
     * @throws Exception
     */
    public function deposit(int $amount, ?string $description = null): array
    {
        $http = $this->client()->post('api/student/finance/deposit', [
            'jurnal_date' => date('Y-m-d'),
            'payment_method' => 'cash',
            'amount' => $amount,
            'description' => $description ?? '',
        ]);

        if ($http->successful() && $http->json('success', false)) {
            return $http->json()['data'];
        }

        return [];
    }

    /**
     * @param int $amount
     * @return array
     */
    public function createInvoice(int $amount): array
    {
        $http = $this->client()->post('/api/finance/va-billing/submit', [
            'deposit' => $amount,
            'payment_method[transfer_amount]' => $amount,
        ]);

        if ($http->successful() && $http->json('success', false)) {
            return $http->json()['data'];
        }

        return [];
    }

    /**
     * @param int         $amount
     * @param string|null $description
     *
     * @return array
     * @throws Exception
     */
    public function withdrawal(int $amount, ?string $description = null): array
    {
        $http = $this->client()->post('api/student/finance/withdrawal', [
            'jurnal_date' => date('Y-m-d'),
            'payment_method' => 'cash',
            'amount' => $amount,
            'description' => $description ?? '',
        ]);

        if ($http->successful() && $http->json('success', false)) {
            return $http->json()['data'];
        }

        return [];
    }

    /**
     * @param int         $to
     * @param int         $amount
     * @param string|null $description
     *
     * @return array
     * @throws Exception
     */
    public function transfer(int $to, int $amount, ?string $description = null): array
    {
        $http = $this->client()->post('api/student/finance/transfer', [
            'user_to' => $to,
            'amount' => $amount,
            'description' => $description ?? '',
        ]);

        if ($http->successful() && $http->json('success', false)) {
            return $http->json()['data'];
        }

        return [];
    }
}
