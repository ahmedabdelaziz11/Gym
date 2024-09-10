<?php

namespace App\Enums;

enum ClientStatus: string
{
    case PENDING = 'PENDING';
    case INTERESTED = 'INTERESTED';
    case NOT_INTERESTED = 'NOT_INTERESTED';
    case QUALIFIED = 'QUALIFIED';
    case NOT_QUALIFIED = 'NOT_QUALIFIED';
    case CONVERTED = 'CONVERTED';
}