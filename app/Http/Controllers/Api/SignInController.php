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
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Fetch the user by email
        $user = User::where('email', $request->email)->first();

        // Check if the user exists
        if (!$user) {
            return response()->json([
                'Status Code' => 404,
                'Message' => 'User not found',
                'Data' => null,
            ], 404);
        }

        // Validate the password
        if (!Hash::check($request->password, $user->password)) {
            Log::info('Password mismatch for email: ' . $request->email);
            return response()->json([
                'Status Code' => 401,
                'Message' => 'Invalid credentials',
                'Data' => null,
            ], 401);
        }

        // Generate a token for the user
        $token = $user->createToken('ZendoToken')->plainTextToken;

        // Return a successful response with the token
        return response()->json([
            'Status Code' => 200,
            'Message' => 'Login successful',
            'Data' => [
                'email' => $user->email,
                'token' => $token,
            ],
        ], 200);
    }
}

