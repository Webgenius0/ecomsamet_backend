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
    public function index(Request $request)
    {
        try {
            $latitude = $request->query('latitude');
            $longitude = $request->query('longitude');
            $radius = $request->query('radius', 10);

            $categories = Category::whereHas('services', function ($query) use ($latitude, $longitude, $radius) {
     if ($latitude && $longitude) {
        $query->whereRaw("
            (6371 * acos(cos(radians(?)) * cos(radians(latitude)) *
            cos(radians(longitude) - radians(?)) + sin(radians(?)) *
            sin(radians(latitude)))) <= ?",
            [$latitude, $longitude, $latitude, $radius]
        );
    }
        })->with(['services' => function ($query) use ($latitude, $longitude, $radius) {
        if ($latitude && $longitude) {
        $query->whereRaw("
            (6371 * acos(cos(radians(?)) * cos(radians(latitude)) *
            cos(radians(longitude) - radians(?)) + sin(radians(?)) *
            sin(radians(latitude)))) <= ?",
            [$latitude, $longitude, $latitude, $radius]
        );
        }
            }])->get();

            return ApiResponse::format(true, 200, 'Services', $categories);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong. Please try again later.'], 500);
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

    public function show(Request $request, $id)
    {
        try {
            $latitude = $request->query('latitude');
            $longitude = $request->query('longitude');
            $radius = $request->query('radius', 10);

            $category = Category::with(['services' => function ($query) use ($latitude, $longitude, $radius) {
                if ($latitude && $longitude) {
                    $query->whereRaw("
                        (6371 * acos(cos(radians(?)) * cos(radians(latitude)) *
                        cos(radians(longitude) - radians(?)) + sin(radians(?)) *
                        sin(radians(latitude)))) <= ?",
                        [$latitude, $longitude, $latitude, $radius]
                    );
                }$query->with('ratings');
            }])->findOrFail($id);

            if ($category->services->isEmpty()) {
                return response()->json(['message' => 'No services found for this category within the given area.'], 404);
            }

            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Services under the category in the specified area',
                'data' => $category->services
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Category not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong.'], 500);
        }
    }


    public function serviceShow($id) {

        // dd($id);
        try {
            $service = Services::with('additionalServices','ratings')->findOrFail($id);
            return ApiResponse::format(true, 200, 'Services under the category', $service);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Service not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong.'], 500);
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



