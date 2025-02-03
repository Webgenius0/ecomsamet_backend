<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Services;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;

class ApiServiceController extends Controller
{
    // Method to get all services
    public function index()
    {
        $services = Services::all();

        return ApiResponse::format(true, 'User registered successfully', $services);
    }

    // // Method to create a new service
    // public function store(Request $request)
    // {

    //     $request->validate([
    //         'category_id' => 'required|exists:categories,id',
    //         'name' => 'required|string|max:255',
    //         'description' => 'nullable|string',
    //         'price' => 'required|numeric|min:0',
    //         'image' => 'nullable|array',
    //         'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    //         'duration' => 'nullable|integer|min:1',
    //     ]);


    //     $imagePaths = [];

    //     // Handle each uploaded image
    //     if ($request->hasFile('image')) {
    //         foreach ($request->file('image') as $image) {
    //             $path = $image->store('services', 'public');
    //             $imagePaths[] = $path;
    //         }
    //     }


    //     $service = Services::create([
    //         'category_id' => $request->category_id,
    //         'name' => $request->name,
    //         'description' => $request->description,
    //         'price' => $request->price,
    //         'image' => $imagePaths,
    //         'duration' => $request->duration,
    //     ]);

    //     return response()->json([
    //         'status' => 201,
    //         'message' => 'Service created successfully',
    //         'service' => $service,
    //     ]);
    // }



    // private function generateImageUrls($imagePaths)
    // {

    //     if (is_string($imagePaths)) {
    //         $imagePaths = json_decode($imagePaths, true);
    //     }


    //     if (!is_array($imagePaths)) {
    //         return [];
    //     }

    //     return array_map(function ($path) {
    //         return asset('storage/' . $path);
    //     }, $imagePaths);
    // }
    //search methods
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

        // Search the services based on query
        $services = Services::where('name', 'like', '%' . $query . '%')
                            ->orWhere('description', 'like', '%' . $query . '%')
                            ->get();

        // If no services are found, return a message
        if ($services->isEmpty()) {
            return response()->json(['message' => 'No services found'], 404);
        }

        // Return the successful search result
        return ApiResponse::format(200, 'Search results', $services);

    } catch (\Exception $e) {
        // Handle any exceptions that occur during the query execution
        return response()->json(['message' => 'Something went wrong. Please try again later.'], 500);
    }
}

public function show($id){

    try {
        // Find the service by ID
        $service = Services::find($id);

        // If the service is not found, return a 404 response
        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }

        // Return the service details
        return ApiResponse::format(200, 'Service details', $service);

    } catch (\Exception $e) {
        // Handle any exceptions that occur during the query execution
        return response()->json(['message' => 'Something went wrong. Please try again later.'], 500);
    }
}



}
