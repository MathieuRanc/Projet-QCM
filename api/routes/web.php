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
