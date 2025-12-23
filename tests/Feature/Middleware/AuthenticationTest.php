<?php

use App\Models\User;

test('protected routes require authentication', function () {
    $response = $this->getJson('/api/v1/me');

    $response->assertStatus(401);
});

test('protected routes work with valid token', function () {
    $user = User::factory()->make(['id' => 1]);

    $response = $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/me');

    $response->assertStatus(200);
});

test('unprotected routes work without authentication', function () {
    $response = $this->getJson('/api/v1/predictions/1');

    // Should not return 401, might return 404 or 200 depending on route
    expect($response->status())->not->toBe(401);
});

