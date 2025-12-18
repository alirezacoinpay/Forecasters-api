<?php

use App\Enums\CategoryStatus;

test('it can convert to array', function () {
    $array = CategoryStatus::toArray();
    
    expect($array)->toBeArray();
    expect($array)->toContain('active');
    expect($array)->toContain('in_active');
});

test('it can find enum by value', function () {
    $result = CategoryStatus::find('active');
    
    expect($result)->toBe('active');
});

