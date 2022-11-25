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

    public function testCreateUser(): void
    {
        $wallet = new WalletService();

        $wallet->admin();

        $user = $wallet->createUser(14, '8574000060118955', 'Yusron Arif', 'yusron3@email.com');

        $this->assertInstanceOf(WalletUser::class, $user);

        $this->assertEquals('8574000060118955', $user->va);
    }
}
