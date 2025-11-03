<?php

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

});