<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ApiUser;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        try {
            // Validate the login credentials
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            // Retrieve credentials
            $credentials = $request->only('email', 'password');
            Log::info('Login attempt with credentials:', $credentials);

            // Find the user by email
            $user = ApiUser::where('email', $request->email)->first();

            // Check if user exists and password is correct
            if (!$user || !Hash::check($request->password, $user->password)) {
                Log::error('Invalid credentials for email: ' . $request->email);
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            // Generate JWT token
            $token = JWTAuth::fromUser($user);

            Log::info('User logged in successfully. Generated token:', ['token' => $token]);
            return response()->json([
                'status' => true,
                'message' => 'User logged in successfully',
                'code' => 200,
                'token_type' => 'bearer',
                'data' => $user,
                'token' => $token,
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            $firstError = collect($e->errors())->flatten()->first();
            Log::error('Validation error during login: ' . $firstError);
            return response()->json([
                'status' => 422,
                // 'message' => 'Validation Failed',
                'message' => $firstError
            ], 422);

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            // Handle JWT exceptions
            Log::error('JWT error during login: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Could not generate token',
                'error' => $e->getMessage()
            ], 500);

        } catch (\Exception $e) {
            // Handle other unexpected errors
            Log::error('Unexpected error during login: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while attempting to log in',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
