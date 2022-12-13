<?php

namespace App\Services\EWallet\Contract;

use App\Services\EWallet\Entity\WalletUser;

interface WalletService
{
    public function login(string $username, string $password): bool;
    public function admin(): ?WalletUser;
}
