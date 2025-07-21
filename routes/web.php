<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Attendance\AttendanceController;
use App\Http\Controllers\Api\ApiTokenController;

Route::middleware('auth:admin')->group(function () {
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/api-keys', [ApiTokenController::class, 'index'])->name('api_tokens.index');
    Route::post('/api-keys', [ApiTokenController::class, 'store'])->name('api_tokens.store');
    Route::delete('/api-keys/{apiToken}', [ApiTokenController::class, 'destroy'])->name('api_tokens.destroy');
});

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });
    Route::get('/login', [AdminAuthController::class, 'showLoginForm']);
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login');

    Route::get('/register', [AdminAuthController::class, 'showRegisterForm']);
    Route::post('/register', [AdminAuthController::class, 'register'])->name('register');

    Route::get('/users', [UserController::class, 'index'])->name('user');

    Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
    Route::post('/user/mass-store', [UserController::class, 'massStore'])->name('user.mass-store');

    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');

});
