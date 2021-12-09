<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\TransferController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// user registration
Route::post('/create_user', [UserController::class, 'store']);

// user balance
Route::middleware('auth:sanctum')->post('/balance_topup', [BalanceController::class, 'topup']);
Route::middleware('auth:sanctum')->get('/balance_read', [BalanceController::class, 'show']);

// transfer
Route::middleware('auth:sanctum')->post('/transfer', [TransferController::class, 'store']);