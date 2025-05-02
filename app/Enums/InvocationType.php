<?php

namespace App\Enums;

enum InvocationType: string
{
    case PAYMENT_TYPE_OPEN = 'open';
    case PAYMENT_TYPE_CLOSE = 'close';
    case MOVE_ACCOUNT_BALANCE = 'move';
}
