<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Rating;
use App\Models\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserHomeController extends Controller
{
    public function index()
    {
        try {
        $user = Auth::user();

        if(!$user) {
            return ApiResponse::format(false, 401, 'Unauthorized: Please log in');
        };



        $services = Services::all();

        return ApiResponse::format(true, 200, 'User registered successfully', $services);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Services not found.'], 500);
        }
    }

    public function search(Request $request)
    {
        try {
            // Validate the query parameter
            $request->validate([
                'query' => 'required|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Capture the first validation error
            $firstError = collect($e->errors())->flatten()->first();
            return response()->json(['message' => $firstError], 400);
        }

        try {
            $query = $request->query('query');

            // Search the services based on the query
            $services = Services::where('service_name', 'like', '%' . $query . '%')
                                ->orWhere('service_details', 'like', '%' . $query . '%')
                                ->get();

            // If no services are found, return a message
            if ($services->isEmpty()) {
                return response()->json(['message' => 'No services found'], 404);
            }

            // Return the successful search result
            return ApiResponse::format(true, 200, 'Search results', $services);

        } catch (\Exception $e) {
            // Handle any exceptions that occur during the query execution
            return response()->json(['message' => 'Something went wrong. Please try again later.'], 500);
        }
    }

public function show($id)
{
    try {
        $category = Category::with('services')->findOrFail($id);

        if ($category->services->isEmpty()) {
            return response()->json(['message' => 'No services found for this category'], 404);
        }

        return ApiResponse::format(true, 200, 'Services under the category', $category->services);

    } catch (\Exception $e) {
        return response()->json(['message' => 'Services not found.'], 500);
    }
}

public function topRating(){
    $user = Auth::user();

    if(!$user) {
        return ApiResponse::format(false, 401, 'Unauthorized: Please log in');
    };

    try {
        $ratings = Rating::orderBy('rating', 'desc')
                         ->take(5)
                         ->get(['id', 'user_id', 'service_id', 'rating', 'comment', 'created_at', 'updated_at']);

        return ApiResponse::format(true, 200, 'Top ratings', $ratings);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error fetching top ratings',
            'error' => $e->getMessage()
        ], 500);
    }

}
}



