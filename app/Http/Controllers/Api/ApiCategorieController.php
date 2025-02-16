<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categorie;

class ApiCategorieController extends Controller
{

    public function index()
    {
        try {
            $categories = Categorie::all();
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server Error',
            ], 500);
        }
    
        return response()->json([
            'status' => 200,
            'message' => 'success',
            'categories' => $categories,
        ]);
       
    }
    public function store(Request $request)
    {
        try {
            $categories = Categorie::all();
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server Error',
            ], 500);
        }
        

        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable',
        ]);

        $categories = Categorie::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json([
            'status' => 201,
            'message' => 'success',
            'category' => $categories
        ]);
    }
    public function getServicesByCategory($category_id)
    {
        // Find the category by ID and load its related services
        $category = Categorie::with('services')->find($category_id);

        // Check if the category exists
        if (!$category) {
            return response()->json([
                'status' => 404,
                'message' => 'Category not found',
            ], 404);
        }

        // Return the category and its related services
        return response()->json([
            'status' => 200,
            'message' => 'success',
            'category' => $category,
            'services' => $category->services,
        ]);
    }
}
