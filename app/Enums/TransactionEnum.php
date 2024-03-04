<?php

namespace App\Enums;

Enum TransactionEnum: string
{
    case PENDING    = 'D';
    case PAID       = 'P';
    case CANCELLED  = 'V';
}