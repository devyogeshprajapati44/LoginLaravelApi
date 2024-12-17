<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Rules\Mobile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class SingUpController extends Controller
{
    
    public function register(Request $request)
    {
        // Define validation rules
        $rules = [
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile' => ['required', 'string', new Mobile, 'unique:users'],
            'password' => 'required|string|min:8|confirmed', // 'confirmed' checks password_confirmation
        ];
    
        // Define custom error messages
        $messages = [
            'username.unique' => 'The username has already been taken.',
            'email.unique' => 'The email is already registered.',
            'mobile.unique' => 'The mobile number is already registered.',
        ];
    
        // Validate the request data
        $validator = Validator::make($request->all(), $rules, $messages);
    
        // If validation fails, return errors in JSON format
        if ($validator->fails()) {
            return response()->json([
                'status_code' => 422,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors(), // Return all validation errors
            ], 422); // 422: Unprocessable Entity
        }
    
        try {
            // Create the user
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'password' => Hash::make($request->password),
            ]);
    
            // Generate the token
            $token = $user->createToken('ZendoToken')->plainTextToken;
    
            // Return success response
            return response()->json([
                'status_code' => 200,
                'message' => 'User registered successfully.',
                'data' => [
                    'token' => $token,
                    'username' => $user->username,
                    'email' => $user->email,
                    'mobile' => $user->mobile,
                ],
            ], 200); // 201: Resource created
        } catch (\Exception $e) {
            // Return failure response if something goes wrong
            return response()->json([
                'success' => 500,
                'message' => 'Registration failed due to a server error.',
                'error' => $e->getMessage(),
            ], 500); // 500: Internal server error
        }
    }
    
}
