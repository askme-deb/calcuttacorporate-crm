<?php

use App\Http\Controllers\api\AttendanceController;
use App\Http\Controllers\AuthenticationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/login', [AuthenticationController::class, 'login']);

Route::get('/users', [AuthenticationController::class, 'index'])->middleware('auth:sanctum');
Route::apiResource('attendence', AttendanceController::class)->middleware('auth:sanctum');
Route::apiResource('getattendence', AttendanceController::class)->middleware('auth:sanctum');
//Route::post('/logout', [AuthenticationController::class, 'logout'])->middleware('auth:sanctum');
// Route::middleware(['auth:sanctum'])->group(function () {
//     Route::apiResource('products', ProductController::class);
// });
