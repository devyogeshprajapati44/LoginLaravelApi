<?php

namespace App\Http\Controllers\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log; // Import Log facade
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\PasswordReset;
use App\Models\User;

class ForgotPasswordController extends Controller
{
 
    //Send email URL and click the reset password

    public function forgetpassword(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'email' => 'required|email|exists:users,email',
            ]);
    
            // Check if the user exists
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json(['success' => false, 'msg' => 'User not found.']);
            }
    
            // Generate a reset token and URL
            $token = Str::random(60);
            $domain = URL::to('/');
            $url = $domain . '/reset-password?token=' . $token;
    
            // Save the token in the password_resets table
            PasswordReset::updateOrCreate(
                ['email' => $request->email],
                [
                    'token' => $token,
                    'created_at' => Carbon::now(),
                ]
            );
    
            // Prepare email data
            $data = [
                'url' => $url,
                'email' => $request->email,
                'title' => "Password Reset",
                'body' => "Please click the link below to reset your password.",
            ];
    
            // Log the email data for debugging
            Log::info('Sending password reset email', $data);
    
            // Send the email
            Mail::send('api.ForgetPasswordMail', ['data' => $data], function ($message) use ($data) {
                $message->to($data['email'])->subject($data['title']);
            });
    
            return response()->json(['success' => true, 'msg' => 'Password reset link sent to your email.']);
            } catch (\Exception $e) {
                Log::error('Error in forgetpassword:', ['message' => $e->getMessage()]);
                return response()->json(['success' => false, 'msg' => $e->getMessage()]);
            }
    }


    //Reset Password User 

    public function resetPassword(Request $request, $token)
    {
        $request->validate([
            'password' => 'required|confirmed|min:8',
        ]);
    
        // Verify the token
        $passwordReset = PasswordReset::where('email', $request->email)->first();

        if (!$passwordReset || $passwordReset->token !== $request->token) {
            return response()->json(['message' => 'Invalid token or email'], 400);
        }

        // Check if token expired (e.g., expires in 60 minutes)
        $expiresAt = Carbon::parse($passwordReset->created_at)->addMinutes(60);
        if (Carbon::now()->greaterThan($expiresAt)) {
            return response()->json(['message' => 'Token has expired'], 400);
        }
    
        // Find the user
        $user = User::where('email', $passwordReset->email)->first();
    
        if (!$user) {
            return response()->json([
                'message' => 'User not found',
                'status' => 'Failed',
            ], 404);
        }
    
        // Reset the user's password
        $user->password = Hash::make($request->password);
        $user->save();
    
        // Delete the reset token
        PasswordReset::where('email', $user->email)->delete();
    
        return response()->json([
            'message' => 'Password reset successfully',
            'status' => 'Success',
        ], 200);
    }
    
}
