<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ApiUser;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // public function register(Request $request)
    // {
    //     $request->validate([
    //         'fullname' => 'required|string',
    //         'date_of_birth' => 'required|integer',
    //         'country' => 'required|string',
    //         'username' => 'required|string|unique:api_users,username',
    //         'email' => 'required|string|email|unique:api_users,email',
    //         'password' => 'required|string|min:8',
    //     ]);

    //     $user = ApiUser::create([
    //         'fullname' => $request->fullname,
    //         'date_of_birth' => $request->date_of_birth,
    //         'country' => $request->country,
    //         'username' => $request->username,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //     ]);

    //     $token = JWTAuth::fromUser($user);

    //     return response()->json([
    //         'message' => 'User registered successfully',
    //         'user' => $user,
    //         'token' => $token,
    //     ], 201);
    // }

    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|string|email',
    //         'password' => 'required|string',
    //     ]);

    //     $credentials = $request->only('email', 'password');
    //     Log::info('Credentials:', $credentials);

    //     $user = ApiUser::where('email', $request->email)->first();

    //     // Check if user exists and password is correct
    //     if (!$user || !Hash::check($request->password, $user->password)) {
    //         Log::error('Invalid credentials for email: ' . $request->email);
    //         return response()->json(['message' => 'Invalid credentials'], 401);
    //     }

    //     // If user and password match, generate token
    //     $token = JWTAuth::fromUser($user);

    //     Log::info('Generated token: ', ['token' => $token]);
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'User logged in successfully',
    //         'code' => 200,
    //         'user' => $user,
    //         'token' => $token,
    //     ]);
    // }

    // public function logout(Request $request)
    // {
    //     // Invalidate the token to log out
    //     try {
    //         JWTAuth::invalidate(JWTAuth::getToken());
    //         return response()->json(['message' => 'User logged out successfully'], 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['message' => 'Failed to log out'], 500);
    //     }
    // }
}
