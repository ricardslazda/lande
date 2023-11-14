<?php

use App\Http\Controllers\API\RPC\LoanScheduleController;
use App\Http\Controllers\API\RPC\RegisterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::prefix('loan-schedule')->group(function () {
        Route::post('calculate', [LoanScheduleController::class, 'calculateLoanSchedule']);
        Route::post('adjust-euribor', [LoanScheduleController::class, 'adjustEuribor']);
    });
});
