<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DigiFlazzController;
use App\Http\Controllers\SaldoController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\TopUpController;

Route::get('/', function () {
    return redirect()->route('dashboard');
})->middleware('auth');


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/cek-respon', [DigiFlazzController::class, 'cekRespon']);
Route::get('/cek-saldo', [SaldoController::class, 'cekSaldo']);

Route::get('/fetch-prices', [PriceController::class, 'fetchAndSavePrices']);
Route::get('/daftar-produk/game', [PriceController::class, 'indexGame'])->name('daftar-produk.game');

Route::get('/fetch-prices/pulsa', [PriceController::class, 'fetchAndSavePulsaPrices']);
Route::get('/daftar-produk/pulsa', [PriceController::class, 'indexPulsa'])->name('daftar-produk.pulsa');

Route::post('/topup', [TopUpController::class, 'topUp']);
Route::get('/topup-form', function () {
    return view('topup_form');
});
Route::get('/topup-game', [TopUpController::class, 'topupViewGame']);


Route::post('/topup-webhook', [TopUpController::class, 'topupWebhook']);
Route::post('/callback', [TopupController::class, 'handleCallback']);



