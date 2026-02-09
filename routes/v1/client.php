<?php
use App\Http\Controllers\Client\{
    CategoryController,
    TopicController,
    TagController,
    PredictionController,
    CommentController,
    CommentLikeController,
    UserPredictionController,
    PredictionLikeController,
    AuthController,
    UserController,
    ActivityController,
    PredictionForwardController,
    FeedController,
    SearchController,
    UserSearchHistoryController,
};
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group( function () {
    Route::post('/login', 'login');
    Route::post('/send-otp', 'sendOtp');
    Route::post('/verify-otp', 'verifyOtp');
});

Route::middleware(['auth:sanctum', 'client'])->group( function () {


    Route::controller(FeedController::class)->group( function () {
        Route::get('/prediction-feed', 'feedPagePredictions');
    });

    Route::controller(PredictionController::class)->group( function () {
        Route::get('/predictions/{id}', 'show');
        Route::post('/predictions', 'store');
    });

    Route::controller(TopicController::class)->group( function () {
        Route::get('/topics', 'index');
        Route::get('/topics/{id}', 'show');
    });

    Route::controller(CategoryController::class)->group( function () {
        Route::get('/categories', 'index');
        Route::get('/categories/{id}', 'show');
    });


    Route::controller(TagController::class)->group( function () {
        Route::get('/tags', 'index');
        Route::get('/tags/{id}', 'show');
    });

    Route::controller(SearchController::class)->group( function () {
        Route::get('/search-history', 'search');
    });

    Route::controller(CommentController::class)->group( function () {
        Route::get('/comments/{id}', 'show');
        Route::post('/comments', 'store');
        Route::put('/comments/{id}', 'update');
        Route::delete('/comments/{id}', 'destroy');
    });

    Route::controller(UserSearchHistoryController::class)->group( function () {
        Route::post('/search-history', 'index');
    });

    Route::controller(CommentLikeController::class)->group( function () {
        Route::post('/comment-likes/{id}/toggle', 'toggle');
    });

    Route::controller(UserController::class)->group( function () {
        Route::get('/me', 'me');
        Route::put('/edit-profile', 'editProfile');
    });

    Route::controller(UserPredictionController::class)->group( function () {
        Route::get('/user-predictions/{id}', 'show');
        Route::get('/user-predictions', 'index');
        Route::post('/user-predictions', 'store');
        Route::put('/user-predictions/{id}', 'update');
        Route::delete('/user-predictions/{id}', 'destroy');
    });

    Route::controller(PredictionLikeController::class)->group( function () {
        Route::post('/prediction-likes/{id}/toggle', 'toggle');
    });

    Route::controller(PredictionForwardController::class)->group( function () {
        Route::post('/prediction-forwards', 'store');
    });

    Route::post('/activity', ActivityController::class)->middleware('throttle:60,1');



});
