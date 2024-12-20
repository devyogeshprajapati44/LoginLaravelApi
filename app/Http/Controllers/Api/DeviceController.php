<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Models\Device;

class DeviceController extends Controller
{
    /**
     * Insert a new device.
     */
    public function deviseName(Request $request)
    {
        // Validate the request data
        $request->validate([
            'device_name' => 'required|string|max:255',
        ]);

        // Create a new device
        $device = new Device();
        $device->device_name = $request->device_name;

        if ($device->save()) {
            return response()->json([
                'status_code' =>200,
                'message' => 'Device added successfully.',
                'data' => $device,
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to add device.',
        ], 500);
    }

    /**
     * Count how many devices have the same name.
     */
    public function countDevicesByName(Request $request)
    {
        // Validate the request data
        $request->validate([
            'device_name' => 'required|string|max:255',
        ]);
    
        // Get the device name from the request
        $device_name = $request->device_name;
    
        $devices = Device::where('device_name', $device_name)->limit(3)->get();
    
        // Count the devices with the same name (up to 3 devices)
        $count = $devices->count();
    
        // Check if any device was found
        if ($count === 0) {
            return response()->json([
                'status_code' => 422,
                'message' => 'Device not found.',
            ], 404);
        }
    
        // Return response with device details
        return response()->json([
            'status_code' =>200,
            'message' => 'Device count retrieved successfully',
            'data' => [
                'id' => $devices->first()->id, // Access the ID of the first device
                'count' => $count,              // The number of devices with the same name
                'device_name' => $device_name,  // The name of the device
            ],
        ]);
    }

}
