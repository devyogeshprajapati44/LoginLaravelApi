<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SingUpController;
use App\Http\Controllers\Api\SignInController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\GetDataController;
use App\Http\Controllers\Api\OTPGenerateController;
use Illuminate\Http\Request;


// Route::get('/', function () {
//     return view('.register');
// });



// User route
Route::get('/user', function (Request $request) {
    return view('user', ['user' => $request->user()]);
});




// Register route
Route::post('register', [SignUpController::class, 'register'])->name('api.register');

// Register route
// Route::post('api/register', [SingUpController::class, 'register'])->name('api.register');

// Get data route
Route::get('/getdata', [GetDataController::class, 'getdata'])->name('getdata');

// Login route
Route::post('/login', [SignInController::class, 'login'])->name('login');

// Change password routes
Route::match(['get', 'post'], '/forgot-password', [ForgotPasswordController::class, 'forgetpassword'])->name('forgot-password');
Route::post('/reset-password/{token}', [ForgotPasswordController::class, 'resetPassword'])->name('reset-password');

// OTP routes
Route::post('/otp-generate', [OTPGenerateController::class, 'otpgenerate'])->name('otp-generate');
Route::post('/otp-verify', [OTPGenerateController::class, 'otpverify'])->name('otp-verify');

