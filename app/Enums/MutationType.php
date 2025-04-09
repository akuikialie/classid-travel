<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Values;

enum MutationType: string
{
    use InvokableCases, Values;

    case IN = "in";
    case OUT = "out";
}
