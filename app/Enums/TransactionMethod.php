<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Values;

enum TransactionMethod: string
{
    use InvokableCases, Values;

    case BANK = "bank";
    case SYSTEM = "system";

    public function name(): string {
        return match ($this) {
            self::BANK => 'Transfer Bank',
            self::SYSTEM => 'Sistem Admin',

        };
    }
}
