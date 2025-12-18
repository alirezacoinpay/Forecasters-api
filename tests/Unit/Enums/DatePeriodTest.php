<?php

use App\Enums\DatePeriod;

test('it can convert to array', function () {
    $array = DatePeriod::toArray();
    
    expect($array)->toBeArray();
    expect($array)->toContain('today');
    expect($array)->toContain('week');
    expect($array)->toContain('month');
});

