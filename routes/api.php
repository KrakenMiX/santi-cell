<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopUpController;

Route::post('/callback', [TopupController::class, 'handleCallback']);



