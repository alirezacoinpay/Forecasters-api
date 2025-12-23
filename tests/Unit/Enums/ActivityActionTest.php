<?php

use App\Enums\ActivityAction;

test('it can convert to array', function () {
    $array = ActivityAction::toArray();
    
    expect($array)->toBeArray();
    expect($array)->toContain(ActivityAction::PREDICTION_CREATE->value);
    expect($array)->toContain(ActivityAction::COMMENT_LIKE->value);
});

test('it can convert to form array', function () {
    $formArray = ActivityAction::toFormArray();
    
    expect($formArray)->toBeArray();
    expect($formArray[ActivityAction::PREDICTION_CREATE->value])->toBe('PREDICTION_CREATE');
});

test('it can find enum by value', function () {
    $result = ActivityAction::find(ActivityAction::PREDICTION_CREATE->value);
    
    expect($result)->toBe('prediction_create');
});

test('it returns null when value not found', function () {
    $result = ActivityAction::find('non_existent');
    
    expect($result)->toBeNull();
});

test('stacks method returns correct actions', function () {
    $stacks = ActivityAction::stacks();
    
    expect($stacks)->toBeArray();
    expect($stacks)->toContain(ActivityAction::COMMENT_LIKE);
    expect($stacks)->toContain(ActivityAction::PREDICTION_LIKE);
});

test('group method returns correct group for engagement actions', function () {
    expect(ActivityAction::COMMENT_LIKE->group())->toBe('engagement');
    expect(ActivityAction::PREDICTION_LIKE->group())->toBe('engagement');
    expect(ActivityAction::LIKE->group())->toBe('engagement');
});

test('label method returns human readable label', function () {
    // The label() method uses ucfirst which only capitalizes first letter
    // So "prediction_create" becomes "Prediction create" not "Prediction Create"
    expect(ActivityAction::PREDICTION_CREATE->label())->toBe('Prediction create');
    expect(ActivityAction::COMMENT_LIKE->label())->toBe('Comment like');
});

