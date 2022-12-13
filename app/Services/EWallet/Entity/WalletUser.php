<?php

namespace App\Services\EWallet\Entity;

class WalletUser
{
    public function __construct(
        public readonly ?int $id = null,
        public readonly ?string $va = null,
        public readonly ?string $name = null,
        public readonly ?string $token = null,
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
