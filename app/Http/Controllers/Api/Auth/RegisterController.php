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

        // Optionally, send OTP after user creation
        $sent = $this->twilioService->sendOtp($request->phone);
        if (!$sent) {
            Log::error("Failed to send OTP to phone: {$request->phone}");
            return response()->json(['message' => 'Failed to send OTP'], 500);
        }
        Log::info("OTP sent to phone: {$request->phone}");

        return ApiResponse::format(true,201, 'User registered successfully', $user);

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

            // Verify OTP
            $isValid = $this->twilioService->verifyOtp($request->phone, $request->otp);

            if (!$isValid) {
                Log::warning("Invalid OTP for phone: {$request->phone}");
                return response()->json(['message' => 'Invalid OTP'], 400);
            }

            // Mark user as verified
            $user = ApiUser::where('phone', $request->phone)->first();
            if ($user) {
                $user->update(['mobile_verfi_otp' => 'verified']);
                return response()->json(['message' => 'Phone number verified successfully'], 200);
            }

            return response()->json(['message' => 'User not found'], 404);

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
