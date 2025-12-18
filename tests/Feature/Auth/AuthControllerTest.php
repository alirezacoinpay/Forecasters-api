<?php

use App\Enums\SmsTemplate;
use App\Facades\Sms;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    $this->repository = Mockery::mock(UserRepositoryInterface::class);
    $this->app->instance(UserRepositoryInterface::class, $this->repository);
    Cache::clear();
    Sms::shouldReceive('sendOtp')->andReturn(true);
});

test('sendOtp creates user if not exists', function () {
    $user = User::factory()->make(['id' => 1, 'mobile' => '09123456789']);
    
    $this->repository->shouldReceive('findByMobileLight')
        ->with('09123456789')
        ->once()
        ->andReturn(null);
    
    $this->repository->shouldReceive('create')
        ->with(['mobile' => '09123456789'])
        ->once()
        ->andReturn($user);
    
    $response = $this->postJson('/api/v1/send-otp', [
        'mobile' => '09123456789',
    ]);
    
    $response->assertStatus(200)
        ->assertJson(['success' => true]);
    
    expect(Cache::has('09123456789'))->toBeTrue();
});

test('sendOtp returns error when OTP not expired', function () {
    $user = User::factory()->make(['id' => 1, 'mobile' => '09123456789']);
    Cache::put('09123456789', '12345', now()->addMinutes(3));
    
    $this->repository->shouldReceive('findByMobileLight')
        ->with('09123456789')
        ->once()
        ->andReturn($user);
    
    $response = $this->postJson('/api/v1/send-otp', [
        'mobile' => '09123456789',
    ]);
    
    $response->assertStatus(400);
});

test('verifyOtp returns token with valid OTP', function () {
    $user = User::factory()->make(['id' => 1, 'mobile' => '09123456789']);
    Cache::put('09123456789', '12345', now()->addMinutes(3));
    
    $this->repository->shouldReceive('findByMobileLight')
        ->with('09123456789')
        ->twice()
        ->andReturn($user);
    
    $response = $this->postJson('/api/v1/verify-otp', [
        'mobile' => '09123456789',
        'otp' => '12345',
    ]);
    
    $response->assertStatus(200)
        ->assertJson(['success' => true])
        ->assertJsonStructure(['data' => ['token', 'user']]);
});

test('verifyOtp returns error with invalid OTP', function () {
    $user = User::factory()->make(['id' => 1, 'mobile' => '09123456789']);
    Cache::put('09123456789', '12345', now()->addMinutes(3));
    
    $this->repository->shouldReceive('findByMobileLight')
        ->with('09123456789')
        ->once()
        ->andReturn($user);
    
    $response = $this->postJson('/api/v1/verify-otp', [
        'mobile' => '09123456789',
        'otp' => '99999',
    ]);
    
    $response->assertStatus(400);
});

test('verifyOtp returns error when user not found', function () {
    $this->repository->shouldReceive('findByMobileLight')
        ->with('09123456789')
        ->once()
        ->andReturn(null);
    
    $response = $this->postJson('/api/v1/verify-otp', [
        'mobile' => '09123456789',
        'otp' => '12345',
    ]);
    
    $response->assertStatus(400);
});

