<?php

use App\Enums\SmsTemplate;
use App\Services\Sms\Providers\MelliPayamakProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Config::set('services.sms.melliPayamak.baseUrl', 'https://test.com/');
    Config::set('services.sms.melliPayamak.username', 'testuser');
    Config::set('services.sms.melliPayamak.password', 'testpass');
    Config::set('services.sms.melliPayamak.fromNumber', '12345');
    Config::set('services.sms.melliPayamak.otpLoginBodyID', '123');
});

test('sendOtp sends HTTP request with correct parameters', function () {
    Http::fake([
        'https://test.com/SendSMS/BaseServiceNumber' => Http::response('Success', 200),
    ]);
    
    $provider = new MelliPayamakProvider();
    $result = $provider->sendOtp('09123456789', '12345', SmsTemplate::LOGIN->value);
    
    Http::assertSent(function ($request) {
        return $request->url() === 'https://test.com/SendSMS/BaseServiceNumber' &&
               $request->hasHeader('Content-Type', 'application/x-www-form-urlencoded') &&
               $request['username'] === 'testuser' &&
               $request['password'] === 'testpass' &&
               $request['to'] === '09123456789' &&
               $request['text'] === '12345' &&
               $request['bodyId'] === '123';
    });
    
    expect($result)->toBeArray();
    expect($result)->toHaveKey('success');
    expect($result)->toHaveKey('message');
    expect($result)->toHaveKey('data');
});

test('sendOtp uses default template when template not recognized', function () {
    Http::fake([
        'https://test.com/SendSMS/BaseServiceNumber' => Http::response('Success', 200),
    ]);
    
    $provider = new MelliPayamakProvider();
    $result = $provider->sendOtp('09123456789', '12345', 'unknown_template');
    
    Http::assertSent(function ($request) {
        return $request['bodyId'] === '123'; // Should use default
    });
    
    expect($result['success'])->toBeTrue();
});

test('sendSms returns array with to and message', function () {
    $provider = new MelliPayamakProvider();
    $result = $provider->sendSms('09123456789', 'Test message');
    
    expect($result)->toBeArray();
    expect($result[0])->toBe('09123456789');
    expect($result[1])->toBe('Test message');
});

