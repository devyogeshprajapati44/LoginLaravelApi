<!-- ForgotPasswordController -->
<?php


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
            $token = Str::random(40);
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

    // Verify the token exists
    $passwordReset = PasswordReset::where('token', $token)->first();

    if (!$passwordReset) {
        return response()->json([
            'message' => 'Token is invalid or expired',
            'status' => 'Failed',
        ], 404);
    }

    // Find the user associated with the token's email
    $user = User::where('email', $passwordReset->email)->first();

    if (!$user) {
        return response()->json([
            'message' => 'User not found',
            'status' => 'Failed',
        ], 404);
    }
    // $user = User::where('email', 'mailtoyogesh44@gmail.com')->first();
    $user->password = Hash::make('password');
    // $user->save();
    // // Update the user's password
    // $user->password = Hash::make($request->password);
    $user->save();

    // Delete the password reset token
    PasswordReset::where('email', $user->email)->delete();

    // Revoke all previous tokens (if using Sanctum)
    $user->tokens()->delete();

    return response()->json([
        'message' => 'Password reset successfully',
        'status' => 'Success',
    ], 200);
}
// ForgotPasswordController

}

// SignInController This login page .

 // Import the Log facade


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


// SignInController This login page .


