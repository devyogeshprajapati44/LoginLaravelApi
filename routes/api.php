<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    SingUpController,
    SignInController,
    ForgotPasswordController,
    OTPGenerateController,
    ImageController,
    OTPGenerate_Mobile_PasswordController,
    GetDataController,
    SwipImageController,
    EmailOtpController,
    DeviceController
};

// Authentication Routes
Route::post('register', [SingUpController::class, 'register'])->name('api.register');
Route::post('login', [SignInController::class, 'login'])->name('api.login');
Route::match(['get', 'post'], 'forgot-password', [ForgotPasswordController::class, 'forgetpassword'])->name('api.forgot_password');
Route::post('reset-password/{token}', [ForgotPasswordController::class, 'resetPassword'])->name('api.reset_password');
Route::get('getdata', [GetDataController::class, 'getdata'])->name('api.getdata');

// OTP Routes

Route::middleware('throttle:5,1')->group(function () {
    Route::post('/generate', [OTPGenerateController::class, 'otpgenerate'])->name('api.generate');
    Route::post('/verify', [OTPGenerateController::class, 'otpverify'])->name('api.verify');
    Route::post('/mobile-password', [OTPGenerate_Mobile_PasswordController::class, 'otpmobile_password'])->name('api.mobile_password');
    Route::post('/mobile-verify-password', [OTPGenerate_Mobile_PasswordController::class, 'otpmobile_password_verify'])->name('api.mobile_verify_password');
});

// Email OTP Code

Route::post('email_forgot-password', [EmailOtpController::class, 'sendResetOTP'])->name('api.sendResetOTP');
Route::post('email_reset-password', [EmailOtpController::class, 'resetPassword'])->name('api.resetPassword');


// Image Upload
Route::post('upload-image', [ImageController::class, 'imgaeupload'])->name('api.upload.image');
Route::post('insert_image', [SwipImageController::class, 'insertImage'])->name('api.insert_image');
Route::get('get_swipe_image', [SwipImageController::class, 'multiple_image'])->name('api.get_swipe_image');


// Devise name
Route::post('devices_name', [DeviceController::class, 'deviseName'])->name('api.devises_name');// Insert data
Route::get('devices_count_name', [DeviceController::class, 'countDevicesByName'])->name('api.get_count_devise_name)');

// Protected Route
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
