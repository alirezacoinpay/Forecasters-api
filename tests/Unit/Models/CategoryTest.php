<?php

use App\Models\Category;
use App\Models\Prediction;
use Tests\TestCase;

uses(TestCase::class);

test('category has predictions relationship', function () {
    $category = Category::factory()->make(['id' => 1]);
    
    $relationship = $category->predictions();
    
    expect($relationship)->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

