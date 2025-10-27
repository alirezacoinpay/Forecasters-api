<?php

namespace App\Enums;

use App\Traits\ArrayableEnum;

enum DatePeriod: string
{
    use ArrayableEnum;
    case TODAY = 'today';
    case WEEK = 'week';
    case MONTH = 'month';
    case QUARTER = 'quarter';
    case YEAR = 'year';
}
