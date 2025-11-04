<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('healthcheck', function(){
    return response()->json([
        'status' => 'success',
        'message' => 'API is working',
        'data' => [],
        'errors' => [],
    ]);
});

Route::prefix('auth')->group(function(){
    
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('password/forget_password', [PasswordController::class, 'forgetPassword'])->name('forget_password');
    Route::post('password/password_reset', [PasswordController::class, 'passwordReset'])->name('password_reset');
});

Route::fallback(function(){
    return response()->json([
            'status' => 'error',
            'message' => 'API Endpoint Not Found',
            'data' => [],
            'error' => []
        ]);
});