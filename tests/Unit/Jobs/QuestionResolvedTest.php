<?php

use App\Jobs\QuestionResolved;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\UserPrediction;
use App\Models\UserPredictionPoints;
use Illuminate\Database\Eloquent\Builder;
use Mockery;

test('handle calculates and inserts prediction points', function () {
    $question = Question::factory()->make(['id' => 1]);
    $correctOption = QuestionOption::factory()->make(['id' => 1, 'is_true' => true]);
    $question->setRelation('questionTrueOption', $correctOption);
    
    $prediction1 = UserPrediction::factory()->make(['id' => 1, 'user_id' => 1, 'question_option_id' => 1]);
    $prediction2 = UserPrediction::factory()->make(['id' => 2, 'user_id' => 2, 'question_option_id' => 1]);
    
    $question->setRelation('userPredictions', collect([$prediction1, $prediction2]));
    
    $query = Mockery::mock(Builder::class);
    $query->shouldReceive('where')->with('question_option_id', 1)->once()->andReturnSelf();
    $query->shouldReceive('count')->once()->andReturn(2);
    
    $question->shouldReceive('userPredictions')->once()->andReturn($query);
    $question->shouldReceive('getAttribute')->with('userPredictions')->andReturn(collect([$prediction1, $prediction2]));
    
    $prediction1->shouldReceive('calculatePoints')->with($question, 2)->once()->andReturn(10);
    $prediction2->shouldReceive('calculatePoints')->with($question, 2)->once()->andReturn(15);
    
    $pointsQuery = Mockery::mock(Builder::class);
    UserPredictionPoints::shouldReceive('query')->once()->andReturn($pointsQuery);
    $pointsQuery->shouldReceive('insert')->with([
        ['user_id' => 1, 'points' => 10],
        ['user_id' => 2, 'points' => 15],
    ])->once()->andReturn(true);
    
    $job = new QuestionResolved($question);
    $job->handle();
    
    expect(true)->toBeTrue();
});

