<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        try {
            // Invalidate the JWT token
            $token = JWTAuth::getToken();

            if (!$token) {
                return response()->json([
                    'status' => false,
                    'message' => 'Token not provided'
                ], 400);
            }

            JWTAuth::invalidate($token);

            Log::info('User logged out successfully', ['token' => $token]);

            return response()->json([
                'status' => true,
                'message' => 'User logged out successfully',
                'code' => 200
            ], 200);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            Log::error('Logout failed: Invalid token', ['error' => $e->getMessage()]);
            return $this->formatErrorResponse('Token is invalid');
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            Log::error('Logout failed: Token expired', ['error' => $e->getMessage()]);
            return $this->formatErrorResponse('Token has already expired');
        } catch (\Exception $e) {
            Log::error('Logout failed: Unexpected error', ['error' => $e->getMessage()]);
            return $this->formatErrorResponse('An unexpected error occurred while logging out');
        }
    }

    /**
     * Format error response with a single message
     */
    private function formatErrorResponse(string $message)
    {
        return response()->json([
            'status' => false,
            'message' => $message
        ], 500);
    }
}
