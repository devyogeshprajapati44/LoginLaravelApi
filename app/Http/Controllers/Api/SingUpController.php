<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Rules\Mobile;
use Illuminate\Support\Facades\Hash;

class SingUpController extends Controller
{
    public function register(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'mobile' => ['required', 'string', new Mobile],
            'password' => 'required|string|min:8|confirmed', // 'confirmed' checks password_confirmation
        ]);

        try {
            // Create the user
            $user = User::create([
                'username' => $validated['username'],
                'email' => $validated['email'],
                'mobile' => $validated['mobile'],
                'password' => Hash::make($validated['password']),
            ]);

            // Generate the token
            $token = $user->createToken('ZendoToken')->plainTextToken;

            // Return success response
            return response()->json([
                'success' => true,
                'token' => $token,
                'message' => 'User registered successfully',
            ], 200); // 201: Resource created
        } catch (\Exception $e) {
            // Return failure response if something goes wrong
            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
                'error' => $e->getMessage(),
            ], 500); // 500: Internal server error
        }
    }
}
