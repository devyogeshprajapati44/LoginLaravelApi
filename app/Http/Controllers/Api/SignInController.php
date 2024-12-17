<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Rules\Mobile;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\Log; // Import the Log facade



class SignInController extends Controller 
{
    public function login(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'login' => 'required', // This field will accept either email or mobile
            'password' => 'required',
        ]);

        // Determine whether the login input is an email or a mobile number
        $loginInput = $request->login;

        if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            // Login input is an email
            $user = User::where('email', $loginInput)->first();
        } else {
            // Login input is treated as a mobile number
            $user = User::where('mobile', $loginInput)->first();
        }

        // Check if the user exists
        if (!$user) {
            return response()->json([
                'status_code' => 404,
                'message' => 'User not found',
                'data' => null,
            ], 404);
        }

        // Validate the password
        if (!Hash::check($request->password, $user->password)) {
            Log::info('Password mismatch for login input: ' . $loginInput);
            return response()->json([
                'status_code' => 401,
                'message' => 'Invalid credentials',
                'data' => null,
            ], 401);
        }

        // Generate a token for the user
        $token = $user->createToken('ZendoToken')->plainTextToken;

        // Return a successful response with the token
        return response()->json([
            'status_code' => 200,
            'message' => 'Login successful',
            'data' => [
                'username' => $user->username,  // Assuming 'username' is a field in User
                'mobile' => $user->mobile,
                'email' => $user->email,
                'token' => $token,
            ],
        ], 200);
    }
}



