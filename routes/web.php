<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LockerController;
use App\Http\Controllers\TransactionController;

// --- Halaman Web User ---
Route::get('/', [WebController::class, 'home'])->name('home');
Route::get('/titip-pilih-loker', [WebController::class, 'titip_pilihLoker'])->name('titip.pilih.loker');
Route::post('/titip-pilih-loker', [WebController::class, 'titip_pilihLokerStore'])->name('titip.pilih.loker.store');
Route::get('/kirim-pilih-loker', [WebController::class, 'kirim_pilihLoker'])->name('kirim.pilih.loker');
Route::post('/kirim-pilih-loker', [WebController::class, 'kirim_pilihLokerStore'])->name('kirim.pilih.loker.store');
Route::get('/titip', [WebController::class, 'formTitip'])->name('form.titip');
Route::post('/titip', [WebController::class, 'titip'])->name('titip.store');
Route::get('/kirim', [WebController::class, 'formKirim'])->name('form.kirim');
Route::post('/kirim', [WebController::class, 'kirim'])->name('kirim.store');

// proses auth admin
Route::get('/admin/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('logout');

// route yang hanya bisa diakses oleh admin login
Route::middleware(['admin.auth'])->group(function () {

    // Dashboard admin
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard.index');
    Route::get('/dashboard/esp-status', [DashboardController::class, 'getEspStatus'])->name('dashboard.espStatus');

    // Mengelola data admin
    Route::get('/admins', [AdminController::class, 'daftarAdmin'])->name('admins.index');
    Route::get('/admins/add', [AdminController::class, 'tambahAdmin'])->name('admins.add');
    Route::post('/admins/store', [AdminController::class, 'storeAdmin'])->name('admins.store');
    Route::get('/admins/edit/{id}', [AdminController::class, 'editAdmin'])->name('admins.edit');
    Route::put('/admins/update/{id}', [AdminController::class, 'updateAdmin'])->name('admins.update');
    Route::delete('/admins/delete/{id}', [AdminController::class, 'destroyAdmin'])->name('admins.destroy');

    // Mengelola data loker
    Route::get('/lockers', [LockerController::class, 'daftarLoker'])->name('lockers.index');
    Route::get('/lockers/add', [LockerController::class, 'tambahLoker'])->name('lockers.add');
    Route::post('/lockers/store', [LockerController::class, 'storeLoker'])->name('lockers.store');
    Route::get('/lockers/edit/{id}', [LockerController::class, 'editLoker'])->name('lockers.edit');
    Route::put('/lockers/update/{id}', [LockerController::class, 'updateLoker'])->name('lockers.update');
    Route::delete('/lockers/delete/{id}', [LockerController::class, 'destroyLoker'])->name('lockers.destroy');
    Route::put('/lockers/{id}/toggle-status', [LockerController::class, 'toggleStatus'])->name('lockers.toggleStatus');

    // Mengelola data transaksi
    Route::get('/transactions', [TransactionController::class, 'daftarTransaksi'])->name('transactions.index');
    Route::get('/transactions/show/{id}', [TransactionController::class, 'showTransaksi'])->name('transactions.show');
    Route::post('/transactions/{id}/buka-locker', [TransactionController::class, 'bukaLoker'])->name('transactions.bukaLoker');
    Route::post('/transactions/{id}/expire', [TransactionController::class, 'ubahExpired'])->name('transactions.expire');
});