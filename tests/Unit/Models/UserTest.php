<?php

use App\Models\User;
use App\Models\UserPrediction;
use Tests\TestCase;

uses(TestCase::class);

test('user has userPredictions relationship', function () {
    $user = User::factory()->make(['id' => 1]);
    
    $relationship = $user->userPredictions();
    
    expect($relationship)->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

test('user can have many predictions', function () {
    $user = User::factory()->make(['id' => 1]);
    $predictions = collect([
        UserPrediction::factory()->make(),
        UserPrediction::factory()->make(),
    ]);
    
    $user->setRelation('userPredictions', $predictions);
    
    expect($user->userPredictions)->toHaveCount(2);
});

