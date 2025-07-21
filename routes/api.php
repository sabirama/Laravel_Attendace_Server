<?php

use App\Http\Controllers\Attendance\AttendanceApiController;
use Illuminate\Support\Facades\Route;

Route::group([], function ($request) {
    Route::post('/attendance/{uid}', [AttendanceApiController::class, 'getByUid']);
});
