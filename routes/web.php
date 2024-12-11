<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SingUpController;
use App\Http\Controllers\Api\SignInController;
use App\Http\Controllers\Api\ForgetPassController;

// Route::get('/', function () {
//     return view('.register');
// });

Route::get('register', [SingUpController::class, 'register']);
// Route::post('/register', [SingUpController::class, 'register']);
Route::post('/login', [SignInController::class, 'login']);
Route::post('/forgotPassword', [ForgetPassController::class, 'forgotPassword']);
