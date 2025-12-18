<?php

use App\Models\Category;
use App\Models\Question;

test('category has questions relationship', function () {
    $category = Category::factory()->make(['id' => 1]);
    
    $relationship = $category->questions();
    
    expect($relationship)->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

