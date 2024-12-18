<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SingUpController;
use App\Http\Controllers\Api\SignInController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\GetDataController;
use App\Http\Controllers\Api\OTPGenerateController;
use App\Http\Controllers\Api\ImageController;


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

// Change password api
Route::match(['get', 'post'], '/forgot-password', [ForgotPasswordController::class, 'forgetpassword']);
Route::post('/reset-password/{token}', [ForgotPasswordController::class, 'resetPassword']);
// OTP code Generate

Route::post('/otp-generate', [OTPGenerateController::class, 'otpgenerate']);
Route::post('/otp-verify', [OTPGenerateController::class, 'otpverify']);

//Image upload file route

Route::post('/upload-image', [ImageController::class, 'imgaeupload']);


