<?php

namespace App\Enums;

use App\Traits\ArrayableEnum;

enum CategoryStatus: string
{
    use ArrayableEnum;
    case ACTIVE = 'active';
    case IN_ACTIVE = 'in_active';
}
