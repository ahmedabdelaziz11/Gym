<?php

namespace App\Enums;

enum CallTypes: string
{
    case FIRST_CALL = 'FIRST_CALL';
    case RENEWAL    = 'RENEWAL';
    case UPGRADE    = 'UPGRADE';
    case FEEDBACK   = 'FEEDBACK';
}