<?php

namespace App\Enums;

enum InvocationStatus: string
{
    case PENDING = 'pending';
    case WAITING_FOR_PAYMENT = 'waiting_payment';
    case PAID = 'paid';

    ##
    case CANCEL = 'cancel';
    case EXPIRED = 'expired';
}
