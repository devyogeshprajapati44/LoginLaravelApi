<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\OTPMobile; // Your OTP model
use Illuminate\Support\Facades\Cache;
use App\Rules\Mobile;
use App\Models\User;

class OTPGenerate_Mobile_PasswordController extends Controller
{
    // Step 1: Generate OTP for Mobile
    public function otpmobile_password(Request $request)
    {
           
            // Validate the request input
            $validator = Validator::make($request->all(), [
                'mobile' => 'required|digits:10|exists:users,mobile',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status_code' => false,
                    'message' => 'Validation error.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Retrieve the user by mobile
            $user = User::where('mobile', $request->mobile)->first();

            // Generate a 6-digit OTP
            $otp = random_int(100000, 999999);
            $mobileNumber = $request->mobile;

            // Store the OTP in the database
            OTPMobile::updateOrCreate(
                ['mobile' => $mobileNumber],
                ['user_id' => $user->id, 'otp' => $otp, 'expires_at' => now()->addMinutes(5)]
            );

            // Cache the OTP for expiration check (optional)
            Cache::put('otp_' . $mobileNumber, $otp, now()->addMinutes(5));

            // Simulate sending the OTP (replace with SMS service)
            // Example: SendSmsService::send($mobileNumber, "Your OTP is: $otp");

            return response()->json([
                'status_code' => true,
                'message' => 'OTP generated successfully.',
                'data' => [
                    'mobile' => $mobileNumber,
                    'otp' => (app()->environment('local', 'staging')) ? $otp : null, // Only expose OTP in non-production environments
                    'expires_at' => now()->addMinutes(5)->toDateTimeString(),
                ],
            ]);
            }

        
    // Verify OTP and Reset Password
    public function otpmobile_password_verify(Request $request)
    {
        // Validate the request input
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|digits:10|exists:users,mobile',
            'otp' => 'required|digits:6',
            'password' => 'required|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status_code' => false,
                'message' => 'Validation error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Retrieve the OTP record
        $otpRecord = OTPMobile::where('mobile', $request->mobile)->first();

        if (!$otpRecord || $otpRecord->otp != $request->otp || now()->greaterThan($otpRecord->expires_at)) {
            return response()->json([
                'status_code' => false,
                'message' => 'Invalid or expired OTP.',
                'data' => null,
            ], 400);
        }

        // Update the user's password
        $user = User::where('mobile', $request->mobile)->first();
        $user->password = bcrypt($request->password);
        $user->save();

        // Delete the OTP record
        $otpRecord->delete();

        return response()->json([
            'status_code' => true,
            'message' => 'Password reset successfully.',
        ]);
    }
 }
    
   
   