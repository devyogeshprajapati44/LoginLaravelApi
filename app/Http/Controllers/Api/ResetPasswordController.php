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
use Illuminate\Support\Facades\Log; // Import Log facade
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\PasswordReset;
use App\Models\User;



class ResetPasswordController extends Controller
{
    /**
     * Reset the user password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
// public function resetPasswordLoad(Request $request)
// {
//     $token = $request->token; // Get the reset token from the request

//     $resetData = PasswordReset::where('token', $token)->first(); // Fetch reset data

//     if ($resetData) {
//         $user = User::where('email', $resetData->email)->first(); // Fetch the user using email

//         if ($user) {
//             if (view()->exists('api.resetPassword')) {
//                 return view('api.resetPassword', compact('user'));
//             } else {
//                 return view('api.404'); // Return 404 if view file is missing
//             }
//         }
//     }

//     return view('api.404'); // Return 404 if token or user is invalid
// }


//Email send password

    public function resetPasswordLoad(Request $request)
    
    {

        $resetData = PasswordReset::where('token',$request->token)->get();

        if(isset($request->token) &&  count($resetData) > 0){

            $user = User::where('email',$resetData[0]['email'])->get();

            return view('api.resetPassword', compact('user'));


        }else{

            return view('api.404');
            
        }
    }

// Reset Password 
public function resetpassword(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::findOrFail($request->id);
        $user->password = bcrypt($request->password);
        $user->save();

        return redirect()->route('login')->with('success', 'Password reset successfully.');
    }

}


