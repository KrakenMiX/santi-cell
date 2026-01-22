<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DigiFlazzController;
use App\Http\Controllers\SaldoController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\TopUpController;
use App\Http\Controllers\PrefixController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KelolaProdukController;
use App\Http\Controllers\KelolaWebsiteController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

// kalau user akses "/" langsung redirect ke dashboard (butuh login)
Route::get('/', function () {
    return redirect()->route('dashboard');
})->middleware('auth');

// ===============
// AUTH (login/logout) → tanpa middleware
// ===============
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

// ===============
// SEMUA ROUTE LAIN WAJIB LOGIN
// ===============
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

    Route::get('/cek-respon', [DigiFlazzController::class, 'cekRespon']);
    Route::get('/cek-saldo', [SaldoController::class, 'cekSaldo']);

    // Digiflazz
    Route::get('/fetch-prices', [PriceController::class, 'fetchAndSavePrices']);
    Route::get('/daftar-produk/game', [PriceController::class, 'indexGame'])->name('daftar-produk.game');
    Route::get('/fetch-vipayment-games', [PriceController::class, 'fetchAndSaveVipaymentGamePrices']);
    Route::get('/fetch-prices/pulsa', [PriceController::class, 'fetchAndSavePulsaPrices']);
    Route::get('/daftar-produk/pulsa', [PriceController::class, 'indexPulsa'])->name('daftar-produk.pulsa');

    Route::post('/topup', [TopUpController::class, 'topUp']);
    Route::get('/topup-pulsa', [TopUpController::class, 'topupViewPulsa'])->name('topup-pulsa');
    Route::get('/cek-prefix', [PrefixController::class, 'checkView'])->name('prefix.check');
    Route::post('/cek-prefix', [PrefixController::class, 'checkPrefix']);

    // Vipayment
    Route::get('/topup-game', [TopUpController::class, 'topupViewGameVip'])->name('topup-game');
    Route::post('/topup-game', [TopUpController::class, 'topUpGameVip'])->name('topup.game.vip.submit');
    Route::post('/cek-nickname', [TopUpController::class, 'cekNickname'])->name('cek.nickname');

    // Transaksi & laporan
    Route::get('/riwayat-transaksi', [TopUpController::class, 'riwayatTransaksi'])->name('riwayat-transaksi');
    Route::get('/transaksi/{id}/detail', [TopUpController::class, 'detail'])->name('transaksi.detail');
    Route::get('/laporan/cetak', [LaporanController::class, 'cetakPDF'])->name('laporan.cetak.pdf');

    // Webhook & callback
    Route::get('/topup-webhook', [TopUpController::class, 'topupWebhook']);
    Route::post('/callback', [TopupController::class, 'handleCallback']);
    Route::post('/callback-vipayment', [TopupController::class, 'handleCallbackVipayment']);

    // Kelola Produk
    Route::prefix('kelola-produk')->group(function () {
        Route::get('/game', [KelolaProdukController::class, 'game'])->name('kelola-produk.game');
        Route::get('/pulsa', [KelolaProdukController::class, 'pulsa'])->name('kelola-produk.pulsa');
    });

    // Kelola Website
    Route::prefix('kelola-website')->group(function () {
        Route::get('/pengguna', [KelolaWebsiteController::class, 'pengguna'])->name('kelola-website.pengguna');
        Route::post('/pengguna', [KelolaWebsiteController::class, 'storePengguna'])->name('pengguna.store');
        Route::delete('/pengguna/{id}', [KelolaWebsiteController::class, 'destroyPengguna'])->name('pengguna.destroy');
        Route::get('/provider', [KelolaWebsiteController::class, 'provider'])->name('kelola-website.provider');
        Route::get('/prefix', [KelolaWebsiteController::class, 'prefix'])->name('kelola-website.prefix');
        Route::get('/supplier', [KelolaWebsiteController::class, 'supplier'])->name('kelola-website.supplier');
    });
});
