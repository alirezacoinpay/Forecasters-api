<?php

use App\Services\Sms\SmsService;
use App\Services\Sms\SmsServiceInterface;
use InvalidArgumentException;
use Mockery;

test('sendOTP delegates to provider', function () {
    $provider = Mockery::mock(SmsServiceInterface::class);
    $service = new SmsService($provider);
    
    $provider->shouldReceive('sendOTP')
        ->with('09123456789', '1234', 'template')
        ->once()
        ->andReturn(true);
    
    $result = $service->sendOTP('09123456789', '1234', 'template');
    
    expect($result)->toBeTrue();
});

test('sendSms delegates to provider', function () {
    $provider = Mockery::mock(SmsServiceInterface::class);
    $service = new SmsService($provider);
    
    $provider->shouldReceive('sendSms')
        ->with(['09123456789'], 'Test message')
        ->once()
        ->andReturn(true);
    
    $result = $service->sendSms(['09123456789'], 'Test message');
    
    expect($result)->toBeTrue();
});

test('setProvider accepts SmsServiceInterface instance', function () {
    $provider1 = Mockery::mock(SmsServiceInterface::class);
    $provider2 = Mockery::mock(SmsServiceInterface::class);
    $service = new SmsService($provider1);
    
    $result = $service->setProvider($provider2);
    
    expect($result)->toBe($service);
});

