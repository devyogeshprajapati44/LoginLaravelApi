<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SwipImage; // Replace this with your actual model name

class SwipImageController extends Controller
{
    //
    public function insertImage(Request $request)
    {
        $request->validate([
            'img_url' => 'required|string', // Validate URL
            'file_path' => 'nullable|string'
        ]);

        $image = SwipImage::create([
            'img_url' => $request->img_url,
            'file_path' => $request->file_path
        ]);

        return response()->json([
            'status_code' => 200,
            'message' => 'Image inserted successfully.',
            'data' => $image
        ], 201);
    }

    public function multiple_image()
    {
        // Fetch all swipe images from the database
        $images = SwipImage::all(['id', 'img_url']); // Adjust 'SwipImage' to your model name
        
        // Prepare and return JSON response
        return response()->json([
            'status_code' => 200,
            'message' => 'Image multiple data retrieved successfully.',
            'data' => $images
        ], 200);
    }
}
