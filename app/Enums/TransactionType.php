<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Options;
use ArchTech\Enums\Values;

enum TransactionType: string
{
    use InvokableCases, Values, Options;

    case MOVE = "move"; // pindah saldo
    case PAYMENT = "payment"; // pembayaran dengan fix amount
    case DEPOSIT = "deposit"; // deposit

    public function code(): string
    {
        return match ($this) {
            self::PAYMENT => 'PAY',
            self::DEPOSIT => 'DP',
            self::MOVE => 'MV',
        };
    }

    public function haveAdminFee(): bool
    {
        return match ($this) {
            self::MOVE, self::PAYMENT => false,
            self::DEPOSIT => true,
        };
    }

}
