<?php

namespace App\Enums;

use App\Traits\ArrayableEnum;

enum SmsTemplate: string
{
    use ArrayableEnum;
    case LOGIN = 'login';
}
