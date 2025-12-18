<?php

use App\Models\Question;
use App\Models\Comment;
use App\Models\QuestionOption;
use App\Models\Tag;

test('question has user relationship', function () {
    $question = Question::factory()->make(['id' => 1]);
    
    $relationship = $question->user();
    
    expect($relationship)->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('question has questionOptions relationship', function () {
    $question = Question::factory()->make(['id' => 1]);
    
    $relationship = $question->questionOptions();
    
    expect($relationship)->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

test('question has comments relationship', function () {
    $question = Question::factory()->make(['id' => 1]);
    
    $relationship = $question->comments();
    
    expect($relationship)->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

test('question has tags relationship', function () {
    $question = Question::factory()->make(['id' => 1]);
    
    $relationship = $question->tags();
    
    expect($relationship)->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsToMany::class);
});

test('question has questionTrueOption relationship', function () {
    $question = Question::factory()->make(['id' => 1]);
    
    $relationship = $question->questionTrueOption();
    
    expect($relationship)->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasOne::class);
});

