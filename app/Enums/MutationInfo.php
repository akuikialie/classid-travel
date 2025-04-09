<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Values;

enum MutationInfo: string
{
    use InvokableCases, Values;

    case DEPOSIT = "deposit";
}
