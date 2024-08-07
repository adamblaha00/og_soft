<?php

use App\Http\Controllers\Api\Book\BookIndexController;
use App\Http\Controllers\Api\SimpleEndpoint\SimpleEndpointGetController;
use App\Http\Controllers\Api\SimpleEndpoint\SimpleEndpointPostController;
use App\Http\Controllers\Api\TaskEstimator\TaskEstimatorController;
use App\Http\Controllers\Api\WorkingDay\WorkingDayController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/simple_endpoint')
    ->group(static function (): void {
        Route::get('/get', SimpleEndpointGetController::class);
        Route::post('/post', SimpleEndpointPostController::class);
    });

Route::prefix('v1/book')
    ->group(static function (): void {
        Route::get('/', BookIndexController::class);
    });

Route::prefix('v1/working_day')
    ->group(static function (): void {
        Route::post('/', WorkingDayController::class);
    });

Route::prefix('v1/task_estimator')
    ->group(static function (): void {
        Route::post('/', TaskEstimatorController::class);
    });
