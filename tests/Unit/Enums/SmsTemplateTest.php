<?php

use App\Enums\SmsTemplate;

test('it can convert to array', function () {
    $array = SmsTemplate::toArray();
    
    expect($array)->toBeArray();
    expect($array)->toContain('login');
});

