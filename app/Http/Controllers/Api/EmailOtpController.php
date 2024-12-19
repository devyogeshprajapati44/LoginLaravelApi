<?php 

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\PasswordReset;

class EmailOtpController extends Controller
{
    // Send Reset OTP
    public function sendResetOTP(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $otp = rand(100000, 999999); // Generate a 6-digit OTP
        $email = $request->email;

        // Store OTP in the password_resets table
        DB::table('password_resets')->updateOrInsert(
            ['email' => $email],
            ['token' => $otp, 'created_at' => now()]
        );

        // Send OTP via email
        Mail::raw("Your OTP code is: $otp", function ($message) use ($email) {
            $message->to($email)
                    ->subject('Password Reset OTP');
        });

        // Response JSON
        return response()->json([
            "status_code" => true,
            "message" => "OTP generated successfully.",
            "data" => [
                "email" => $email,
                "otp" => $otp,
                "expires_at" => now()->addMinutes(15)->toDateTimeString()
            ]
        ]);
    }

    // Reset Password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        // Validate OTP
        $reset = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->otp)
            ->first();

        if (!$reset || now()->diffInMinutes($reset->created_at) > 15) {
            return response()->json([
                "status_code" => false,
                "message" => "Invalid or expired OTP.",
                "errors" => [
                    "otp" => "The provided OTP is incorrect or expired."
                ]
            ], 400);
        }

        // Update password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the password reset record
        DB::table('password_resets')->where('email', $request->email)->delete();

        return response()->json([
            "status_code" => true,
            "message" => "Password reset successfully.",
            "data" => [
                "email" => $request->email,
                "reset_at" => now()->toDateTimeString()
            ]
        ]);
    }
}
