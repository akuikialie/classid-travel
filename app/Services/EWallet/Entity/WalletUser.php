<?php

namespace App\Services\EWallet\Entity;

class WalletUser
{
    public function __construct(
        public readonly int|null $id = null,
        public readonly string|null $bcn = null,
        public readonly string|null $va = null,
        public readonly string|null $name = null,
        public readonly string|null $token = null,
        private readonly bool $isAdmin = false
    ) {}

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }
}
