<?php

namespace Tests\Feature\Wallet;

use App\Services\EWallet\WalletService;
use Tests\TestCase;

class InitialTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testConnection(): void
    {
        $wallet = new WalletService();

        // dump("base-url : " . $wallet->getBaseUrl());
        $this->assertIsString($wallet->getBaseUrl(), 'base-url');

        $this->assertTrue($wallet->ping(), 'success ping');
    }
}