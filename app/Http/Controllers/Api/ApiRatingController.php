<?php

namespace App\Http\Controllers\APi;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ApiRatingController extends Controller
{

    public function index() {
        try {
            // Use the 'api' guard if using token-based authentication
            $user = Auth::user();

            if ($user) {
                $ratings = Rating::where('user_id', $user->id)->get();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Ratings retrieved successfully',
                    'data' => $ratings
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated'
                ], 401);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve ratings',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function rateService(Request $request)
{
    try {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not authenticated'
            ], 401);
        }

        // Validate request data
        $validatedData = $request->validate([
            'service_id' => 'required|exists:services,id',
            'rating' => 'required|numeric|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        // Manually set user_id
        $validatedData['user_id'] = $user->id;

        // Debugging: Check if user & service exist
        if (!\App\Models\User::find($user->id)) {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 400);
        }
        if (!\App\Models\Services::find($validatedData['service_id'])) {
            return response()->json(['status' => 'error', 'message' => 'Service not found'], 400);
        }

        // Create rating
        $rating = Rating::create($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'Rating created successfully',
            'data' => $rating
        ], 201);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Validation failed',
            'errors' => $e->errors(),
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to create rating',
            'error' => $e->getMessage()
        ], 500);
    }
}



}
