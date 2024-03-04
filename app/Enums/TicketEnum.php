<?php

namespace App\Enums;

Enum TicketEnum: string
{
    case AVAILABLE  = 'A';
    case PENDING    = 'D';
    case BOUGHT   = 'B';
}