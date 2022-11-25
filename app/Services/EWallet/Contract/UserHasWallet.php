<?php

namespace App\Services\EWallet\Contract;

interface UserHasWallet
{
    public function login(string $username, string $password): array;
    public function admin(): array;
}