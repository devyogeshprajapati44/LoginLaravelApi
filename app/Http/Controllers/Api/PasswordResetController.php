<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PasswordReset;
use App\Models\User;
use Laravel\Sanctum\HasApiTokens;

class PasswordResetController extends Controller
{
    public function resetPasswordLoad(Request $request)
    
    {
        
        $resetData = PasswordReset::where('token', $request->token)->first();
        // $resetData = PasswordReset::where('token',$request->token)->get();
        //dd($resetData);

        if ($resetData) { // Check if the instance exists
            $users = User::where('email', $resetData->email)->get();
        
            return view('api.resetPassword', compact('users'));
        }
        
        return view('api.404');
    }

// Reset Password 
// Handles the password reset
public function resetPassword(Request $request)
{
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $resetData = PasswordReset::where('token', $request->token)->first();

    if (!$resetData || $resetData->email !== $request->email) {
        return response()->json(['error' => 'Invalid token or email'], 400);
    }

    $user = User::where('email', $request->email)->first();
    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    $user->password = bcrypt($request->password);
    $user->setRememberToken(Str::random(60));
    $user->save();

    // Optionally delete the token after successful reset
    PasswordReset::where('email', $request->email)->delete();

    return response()->json(['message' => 'Password reset successfully'], 200);
}


}
