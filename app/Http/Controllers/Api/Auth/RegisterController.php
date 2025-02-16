<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\ApiUser;
use App\Services\TwilioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Helpers\ApiResponse;

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
                'fullname' => 'required|string|max:255',
                'phone' => 'required|string|unique:api_users,phone',
                'email' => 'required|email|unique:api_users,email',
                'password' => 'required|string|min:8|confirmed',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            // Upload image if provided
            $imagePaths = [];

            // Handle each uploaded image
            if ($request->hasFile('image')) {
                foreach ($request->file('image') as $image) {
                    $path = $image->store('profile_images', 'public');
                    $imagePaths[] = $path;
                }
            }

            // Create user record
            $user = ApiUser::create([
                'fullname' => $request->fullname,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'image' => json_encode($imagePaths),
            ]);

            return ApiResponse::format(true, 'User registered successfully', $user);



            // Send OTP
            $sent = $this->twilioService->sendOtp($request->phone);
            if (!$sent) {
                Log::error("Failed to send OTP to phone: {$request->phone}");
                return response()->json(['message' => 'Failed to send OTP'], 500);
            }
            Log::info("OTP sent to phone: {$request->phone}");


            return response()->json([
                'message' => 'OTP sent successfully. Please verify your phone number.',
                'user_id' => $user->id
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            $firstError = collect($e->errors())->flatten()->first();
            Log::error('Validation error during registration: ' . $firstError);
            return response()->json([
                // 'message' => 'Validation failed',
                'message' => $firstError
            ], 422);

        } catch (\Exception $e) {
            Log::error('Unexpected error during registration: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred during registration',
                'error' => $e->getMessage()
            ], 500);
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
