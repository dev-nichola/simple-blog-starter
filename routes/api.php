<?php

use App\Http\Controllers\Api\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register', RegisterController::class)->name('register');

Route::get('/user', function (Request $request) {
    return $request->user();
    })->middleware('auth:sanctum');

Route::get('/hello', function() {
    return response()->json([
        "statusCode" => 200,
        "message" => "Hello World",
    ]);
});
