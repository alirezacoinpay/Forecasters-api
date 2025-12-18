<?php

use App\Enums\TopicStatus;

test('it can convert to array', function () {
    $array = TopicStatus::toArray();
    
    expect($array)->toBeArray();
    expect($array)->toContain('active');
    expect($array)->toContain('in_active');
});

