<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\ApiUser;
use App\Services\TwilioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Helpers\ApiResponse;
use App\Models\User;

class RegisterController extends Controller
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }

    // Step 1: Register User and Send OTP
    public function register(Request $request)
{
    // dd($request);
    try {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone,NULL,id,role,' . request()->role,
            'email' => 'required|email|unique:users,email,NULL,id,role,' . request()->role,
            'password' => 'required|string|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'role' => 'required|in:user,hairdresser',
        ]);

        // Handle avatar upload if provided
        if ($request->hasFile('avatar')) {
            $imagePath = $request->file('avatar')->store('profile_images', 'public');
            $imageData = $imagePath; // Store the image path
        } else {
            $imageData = null; // No image uploaded, set to null
        }

        // Create user record
        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'avatar' => $imageData,
        ]);
        // Set static OTP (e.g., '1234')
        $otp = '1234';

// Assign the static OTP to the 'otp' field (since the column in the table is 'otp')
$user->otp = $otp;
$user->save();

Log::info("Static OTP {$otp} assigned to user with phone: {$request->phone}");

// Return the response indicating OTP was set (simulated)
return ApiResponse::format(true, 201, 'User registered successfully.', ['otp' => $user->otp]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        $firstError = collect($e->errors())->flatten()->first();
        Log::error('Validation error during registration: ' . $firstError);
        return response()->json(['message' => $firstError], 422);

    } catch (\Exception $e) {
        Log::error('Unexpected error during registration: ' . $e->getMessage());
        return response()->json(['message' => 'An error occurred during registration', 'error' => $e->getMessage()], 500);
    }
}


    // Step 2: Verify OTP
    public function verifyOtp(Request $request)
{
    try {
        // Validate the request
        $request->validate([
            'phone' => 'required|string',
            'otp' => 'required|string',
        ]);

        // Retrieve the user by phone number
        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            Log::warning("User not found for phone: {$request->phone}");
            return response()->json(['message' => 'User not found'], 404);
        }

        // Verify OTP (use the stored OTP in the database)
        if ($user->otp !== $request->otp) {
            Log::warning("Invalid OTP for phone: {$request->phone}");
            return response()->json(['message' => 'Invalid OTP'], 400);
        }

        // Mark user as verified
        $user->update(['mobile_verfi_otp' => 'verified']);

        Log::info("Phone number verified successfully for phone: {$request->phone}");

        return response()->json(['message' => 'Phone number verified successfully'], 200);

    } catch (\Illuminate\Validation\ValidationException $e) {
        $firstError = collect($e->errors())->flatten()->first();
        Log::error('Validation error during OTP verification: ' . $firstError);
        return response()->json([
            'message' => 'Validation failed',
            'error' => $firstError
        ], 422);

    } catch (\Exception $e) {
        Log::error('Unexpected error during OTP verification: ' . $e->getMessage());
        return response()->json([
            'message' => 'An error occurred during OTP verification',
            'error' => $e->getMessage()
        ], 500);
    }
}

}
