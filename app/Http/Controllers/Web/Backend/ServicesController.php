<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Services;
use App\Models\Categorie;
use Illuminate\Support\Facades\Storage;

class ServicesController extends Controller
{
    // Method to get all services
    public function index()
    {
        $services = Services::all();

        // Iterate over the services to generate full URLs for images
        foreach ($services as $service) {
            if (!empty($service->image)) {
                $service->image = $this->generateImageUrls($service->image); // Convert array of image paths to array of URLs
            }
        }

        return view('backend.layouts.services.index', compact('services'));
    }

    // Method to create a new service
    public function store(Request $request)
    {

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|array',
            'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'duration' => 'nullable|string',
        ]);


        $imagePaths = [];

        // Handle each uploaded image
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $image) {
                $path = $image->store('services', 'public');
                $imagePaths[] = $path;
            }
        }


        $service = Services::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imagePaths,
            'duration' => $request->duration,
        ]);


        return redirect()->route('service.index');
    }



    private function generateImageUrls(array $images): array
{
    $imageUrls = [];
    foreach ($images as $image) {
        $imageUrls[] = asset('storage/' . $image); // Generate full URL for each image
    }
    return $imageUrls;
}

    public function destroy($id)
    {
        $service = Services::find($id);
        $service->delete();

        return redirect()->route('service.index');
    }
    public function update(Request $request, $id)
{
    // Find the service
    $service = Services::find($id);

    if (!$service) {
        return redirect()->route('service.index')->with('error', 'Service not found.');
    }

    // Validate the incoming request
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric',
        'category_id' => 'required|integer',
        'duration' => 'required|string',
        'image.*' => 'image|mimes:jpeg,png,jpg|max:2048', // Validate new images
    ]);

    // Handle image deletion
    $images = $service->image ?? []; // Get current images as an array
    if ($request->has('delete_images')) {
        foreach ($request->delete_images as $index) {
            if (isset($images[$index])) {
                // Delete the image from storage
                $imagePath = 'services/' . $images[$index];
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
                unset($images[$index]); // Remove the image from the array
            }
        }

        // Re-index the array to avoid gaps in keys
        $images = array_values($images);
    }

    // Handle new image uploads
    if ($request->hasFile('image')) {
        foreach ($request->file('image') as $file) {
            $path = $file->store('services', 'public');
            $images[] = $path; // Add the new image path to the array
        }
    }

    // Update service details
    $service->name = $request->name;
    $service->description = $request->description;
    $service->price = $request->price;
    $service->category_id = $request->category_id;
    $service->duration = $request->duration;

    // Update the image attribute
    $service->image = $images;

    // Save the service
    $service->save();

    return redirect()->route('service.index')->with('success', 'Service updated successfully!');
}




    public function edit($id)
    {
        $service = Services::find($id);
        $categories = Categorie::all();

        return view('backend.layouts.services.edit', compact('service', 'categories'));
    }
    public function create()
    {
        // Fetch all categories dynamically
        $categories = Categorie::all();

        // Pass the categories to the blade file
        return view('backend.layouts.services.create', compact('categories'));
    }



}
