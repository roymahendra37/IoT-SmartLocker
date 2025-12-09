<?php

use App\Http\Controllers\SmartLockerController;
use Illuminate\Support\Facades\Route;

Route::middleware('smartlocker.key')->group(function () {
    Route::post('/scan', [SmartLockerController::class, 'scan']);
    Route::get('/lock-status', [SmartLockerController::class, 'lockStatus']);
});