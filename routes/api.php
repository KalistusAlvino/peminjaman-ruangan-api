<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Peminjaman\BookingController;
use App\Http\Controllers\Ruangan\RoomController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:api')->group(function () {
    Route::middleware('role:admin,pengguna')->group(function () {
        Route::get('/rooms', [RoomController::class, 'index'])->name('bookings.index');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
    Route::middleware('role:pengguna')->group(function () {
        Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    });
    Route::middleware('role:admin')->group(function () {
        Route::put('/bookings/approve/{id}', [BookingController::class, 'approve'])->name('bookings.approve');
        Route::put('/bookings/reject/{id}', [BookingController::class, 'reject'])->name('bookings.reject');
    });

});


