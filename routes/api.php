<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SingUpController;
use App\Http\Controllers\Api\SignInController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\GetDataController;



Route::get('/user', function (Request $request) {
    return $request->user();
    
})->middleware('auth:sanctum');
// Route::post('/register', function () {
//     return response()->json(['message' => 'Route works!']);
// });

Route::post('register', [SingUpController::class, 'register']);
Route::get('getdata', [GetDataController::class, 'getdata']);
// Route::post('/register', [SingUpController::class, 'register']);
Route::post('/login', [SignInController::class, 'login']);

//Route::post('/Password', [PasswordController::class, 'Password']);
// Define routes for sending reset link and resetting password
//Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);

//Route::post('/forgot-password', [ForgotPasswordController::class, 'forgetpassword']);
Route::match(['get', 'post'], '/forgot-password', [ForgotPasswordController::class, 'forgetpassword']);

Route::post('/reset-password/{token}', [ForgotPasswordController::class, 'resetPassword']);

// Route::get('/reset-password', [PasswordResetController::class, 'resetPasswordLoad'])->name('reset-password.load');
// Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);

