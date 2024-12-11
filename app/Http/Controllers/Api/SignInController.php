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
            return response()->json(['message' => 'User not found'], 404);
        }
    
        
        // Validate the password
        if (!Hash::check($request->password, $user->password)) {
            Log::info('Password mismatch for email: ' . $request->email);
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    
        // Generate a token for the user
        $token = $user->createToken('ZendoToken')->plainTextToken;
    
        // Return a successful response with the token
        return response()->json([
            'success' => true,
            'token' => $token,
            'message' => 'Logged in successfully',
        ]);
    }
    
}
