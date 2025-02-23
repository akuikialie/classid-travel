<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Options;
use ArchTech\Enums\Values;

enum TransactionType: string
{
    use InvokableCases, Values, Options;

    case PAYMENT = "payment"; // pembayaran billing
    case A = "a"; // pembayaran billing

}
