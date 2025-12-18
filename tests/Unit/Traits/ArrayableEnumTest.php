<?php

use App\Traits\ArrayableEnum;

enum TestEnum: string
{
    use ArrayableEnum;
    
    case FIRST = 'first';
    case SECOND = 'second';
    case THIRD = 'third';
}

test('toArray returns array of enum values', function () {
    $array = TestEnum::toArray();
    
    expect($array)->toBeArray();
    expect($array)->toContain('first');
    expect($array)->toContain('second');
    expect($array)->toContain('third');
});

test('toFormArray returns array with value as key and name as value', function () {
    $formArray = TestEnum::toFormArray();
    
    expect($formArray)->toBeArray();
    expect($formArray['first'])->toBe('FIRST');
    expect($formArray['second'])->toBe('SECOND');
});

test('find returns lowercase name when value exists', function () {
    $result = TestEnum::find('first');
    
    expect($result)->toBe('first');
});

test('find returns null when value does not exist', function () {
    $result = TestEnum::find('nonexistent');
    
    expect($result)->toBeNull();
});

