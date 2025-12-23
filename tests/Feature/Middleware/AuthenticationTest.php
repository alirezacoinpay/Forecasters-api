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

test('protected routes return 401 without authentication', function () {
    $response = $this->getJson('/api/v1/predictions/1');

    // All routes except auth routes require authentication
    $response->assertStatus(401);
});

