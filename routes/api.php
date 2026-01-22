<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopUpController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/callback', [TopupController::class, 'handleCallback']);
Route::post('/callback-vipayment', [TopupController::class, 'handleCallbackVipayment']);