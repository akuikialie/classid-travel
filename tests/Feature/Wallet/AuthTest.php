<?php

namespace Tests\Feature\Wallet;

use App\Services\EWallet\Entity\WalletUser;
use App\Services\EWallet\WalletService;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAdmin(): void
    {
        $wallet = new WalletService();

        $admin = $wallet->admin();

        $this->assertInstanceOf(WalletUser::class, $admin);

        $this->assertTrue($admin->isAdmin());

        $this->assertStringContainsStringIgnoringCase('sidik', $admin->name);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testCreateUser(): void
    {
        $wallet = new WalletService();

        $wallet->admin();

        $wallet->login();

        $user = $wallet->createUser("jamaah-14", '8574000060118956', 'Yusron Arif - ', 'string@prohajj.app');

        $this->assertInstanceOf(WalletUser::class, $user);

        $this->assertEquals('8574000060118956', $user->va);
    }
}
