<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SignUpController;
use App\Http\Controllers\Api\SignInController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\GetDataController;
use App\Http\Controllers\Api\OTPGenerateController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Api\ImageController;


// General Login View
Route::get('api/login', function (Request $request) {
    return view('user', ['user' => $request->user()]);
  
});

// Route::get('api/register', function (Request $request) {
//     return view('register', ['user' => $request->user()]);
// });

//Route::post('/upload-image', [ImageController::class, 'imgaeupload']);
Route::get('api/upload-image', function (Request $request) {
    return view('api.imgaeupload', ['user' => $request->user()]);
});

Route::get('api/get_count_devise_name', function (Request $request) {
    return view('api.get_count_devise_name', ['user' => $request->user()]);
});

Route::get('api/devices_name', function (Request $request) {
    return view('api.devices_name', ['user' => $request->user()]);
});

Route::get('api/register', function (Request $request) {
    return view('api.register', ['user' => $request->user()]);
});

// Register Route

//Route::match(['get', 'post'], 'api/register', [SignUpController::class, 'api.register']);
//Route::post('api/register', [SignUpController::class, 'register'])->name('api.register');

// // Login Route
// Route::post('/login', [SignInController::class, 'login'])->name('login');

// // Forgot Password Route
// Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.forgot');

// // Password Reset Route
// Route::post('reset-password', [PasswordResetController::class, 'reset'])->name('password.reset');

// // OTP Generation Route
// Route::post('generate-otp', [OTPGenerateController::class, 'generate'])->name('otp.generate');

// // Get Data Route
// Route::get('get-data', [GetDataController::class, 'getData'])->name('api.getData');
