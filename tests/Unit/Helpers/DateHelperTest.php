<?php

use App\Helpers\DateHelper;
use Carbon\Carbon;

test('shortTimeAgo returns seconds for less than 60 seconds', function () {
    $date = Carbon::now()->subSeconds(30);
    $result = DateHelper::shortTimeAgo($date);
    
    expect($result)->toBe('30s');
});

test('shortTimeAgo returns minutes for less than 60 minutes', function () {
    $date = Carbon::now()->subMinutes(30);
    $result = DateHelper::shortTimeAgo($date);
    
    expect($result)->toBe('30m');
});

test('shortTimeAgo returns hours for less than 24 hours', function () {
    $date = Carbon::now()->subHours(5);
    $result = DateHelper::shortTimeAgo($date);
    
    expect($result)->toBe('5h');
});

test('shortTimeAgo returns days for less than 30 days', function () {
    $date = Carbon::now()->subDays(10);
    $result = DateHelper::shortTimeAgo($date);
    
    expect($result)->toBe('10d');
});

test('shortTimeAgo returns months for less than 12 months', function () {
    $date = Carbon::now()->subMonths(6);
    $result = DateHelper::shortTimeAgo($date);
    
    expect($result)->toBe('6mo');
});

test('shortTimeAgo returns years for 12+ months', function () {
    $date = Carbon::now()->subYears(2);
    $result = DateHelper::shortTimeAgo($date);
    
    expect($result)->toBe('2y');
});

