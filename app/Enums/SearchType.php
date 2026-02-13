<?php
namespace App\Enums;

use App\Traits\ArrayableEnum;

enum SearchType: string
{
    use ArrayableEnum;
    case TEXT = 'text';

    case TAG = 'tag';
    case TOPIC = 'topic';
    case CATEGORY = 'category';
}
