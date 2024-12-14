<?php

use App\Http\Controllers\Api\BookingTransactionController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\HomeServiceController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/service/{homeService:slug}', [HomeServiceController::class,'show']);
Route::apiResource('/services',HomeServiceController::class);

Route::get('/category/{category:slug}', [CategoryController::class,'show']);
Route::apiResource('/categories',CategoryController::class);

Route::post('/booking-transaction', [BookingTransactionController::class,'store']);
Route::post('/check-booking', [BookingTransactionController::class,'booking_details']);
