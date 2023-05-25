<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;

Route::group(['prefix' => 'quiz'], function () {
    Route::get('/', [QuizController::class, 'getQuiz']);
    Route::post('/', [QuizController::class, 'create']);
    Route::delete('/', [QuizController::class, 'delete']);
    Route::post('/correct', [QuizController::class, 'correct']);
    Route::post('/omr_errors_resolved', [QuizController::class, 'omr_errors_resolved']);

    Route::post('/upload_copies', [QuizController::class, 'upload_copies']);
    Route::post('/upload_correction', [QuizController::class, 'upload_correction']);
    Route::post('/upload_list', [QuizController::class, 'upload_list']);
    Route::post('/check_correction', [QuizController::class, 'check_correction']);
});

Route::get('/db-test', function () {
    try {
        DB::connection()->getPdo();
        return response()->json(['message' => 'Successfully connected to the DB.'], 200);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Could not connect to the DB: ' . $e->getMessage()], 500);
    }
});
