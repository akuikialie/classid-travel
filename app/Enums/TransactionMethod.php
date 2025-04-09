<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Values;

enum TransactionMethod: string
{
    use InvokableCases, Values;

    case BANK = "bank";
}
