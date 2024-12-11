<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Rules\Mobile;
use Laravel\Sanctum\HasApiTokens;

class GetDataController extends Controller
{
    public function getdata(Request $request)
    {
 // Retrieve all users from the database
        $users = User::all();

        // Check if no users are found
        if ($users->isEmpty()) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'No users found'
            ], 404); // 404 indicates no resource found
        }

        // Return the users as a JSON response
        return response()->json([
            'success' => true,
            'data' => $users,
            'message' => 'User data retrieved successfully'
        ], 200); // 200 is the HTTP status code for a successful response

    
}
}

