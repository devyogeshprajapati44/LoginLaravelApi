<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\Image;


class ImageController extends Controller
{
    public function imgaeupload(Request $request)
    {
                // Validate the request
            $request->validate([
                'image' => 'required|image|mimes:jpg,jpeg,png|max:2048', // 2MB max
            ]);

            // Store the image in the 'public/images' directory
            $filePath = $request->file('image')->store('images', 'public');

            // Save image details in the database
            $image = Image::create([
                'file_name' => $request->file('image')->getClientOriginalName(),
                'file_path' => $filePath,
            ]);

        // Return JSON response
            return response()->json([
                'success' => true,
                'message' => 'Image uploaded successfully!',
                'data' => [
                    'id' => $image->id,
                    'file_name' => $image->file_name,
                    'file_path' => asset('storage/' . $image->file_path),
                ],
            ]);
     }
}
