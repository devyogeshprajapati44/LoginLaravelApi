<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\OTPMobile;

class OTPGenerateController extends Controller
{
    // Generate OTP for an existing mobile number and email
    public function otpgenerate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|digits:10',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'Status Code' => false,
                'Message' => $validator->errors(), // Validation errors
                'Data' => null
            ], 400);
        }

        // Check if a user exists with the provided email
        $user = User::where('email', $request->email)->first();

        // Check if the email exists
        if (!$user) {
            return response()->json([
                'Status Code' => false,
                'Message' => 'Email not found.',
                'Data' => null
            ], 404);
        }

        // Check if the mobile number matches the existing user
        if ($user->mobile != $request->mobile) {
            return response()->json([
                'status' => false,
                'message' => 'The provided mobile number does not match the registered one for this email.',
                'data' => null
            ], 400);
        }

        // If both email and mobile match, proceed to generate OTP
        $otp = random_int(100000, 999999);
        $mobileNumber = $request->mobile;

        // Create OTP record
        OTPMobile::create([
            'user_id' => $user->id,
            'mobile' => $mobileNumber,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(5),
        ]);

        // Cache the OTP for expiration check
        Cache::put('otp_' . $mobileNumber, $otp, now()->addMinutes(5));

        $response = [
            'Status Code' => true,
            'Message' => 'OTP generated successfully.',
            'Data' => [
                'mobile' => $mobileNumber,
                'otp' => (app()->environment('local', 'staging')) ? $otp : null, // Only show OTP in local/staging environments
                'expires_at' => now()->addMinutes(5)->toDateTimeString(),
            ]
        ];

        return response()->json($response);
    }

    // Verify OTP for a mobile number (no email required)
    public function otpverify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|digits:10',
            'otp' => 'required|digits:6',
        ]);

        // Validation failed
        if ($validator->fails()) {
            return response()->json([
                'Status Code' => false,
                'Message' => $validator->errors(), // Validation errors
                'Data' => null
            ], 400);
        }

        $mobileNumber = $request->mobile;

        // Check OTP from cache or database
        $storedOtp = Cache::get('otp_' . $mobileNumber); // Cached OTP
        if (!$storedOtp) {
            // If OTP not found in cache, check the database for valid OTP
            $otpRecord = OTPMobile::where('mobile', $mobileNumber)
                ->where('otp', $request->otp)
                ->where('expires_at', '>', now())
                ->first();

            if (!$otpRecord) {
                return response()->json([
                    'Status Code' => false,
                    'Message' => 'The OTP is either invalid or has expired. Please request a new OTP.',
                    'Data' => null
                ], 400);
            }

            // Set storedOtp if found in database
            $storedOtp = $otpRecord->otp;
        }

        // Verify the OTP
        if ($storedOtp == $request->otp) {
            // OTP is valid: Delete OTP record and clear cache
            OTPMobile::where('mobile', $mobileNumber)->where('otp', $request->otp)->delete();
            Cache::forget('otp_' . $mobileNumber);

            return response()->json([
                'Status Code' => true,
                'Message' => 'OTP verified successfully.',
                'Data' => [
                    'mobile' => $mobileNumber,
                    'otp_verified' => true,
                    'expires_at' => null
                ]
            ]);
        }

        // OTP mismatch
        return response()->json([
            'Status Code' => false,
            'Message' => 'Invalid OTP.',
            'Data' => null
        ], 400);
    }}

