<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\AdditionalService;
use App\Models\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HairDressServicesController extends Controller
{
    public function index()
{
    $user = Auth::user();

    if (!$user) {
        return ApiResponse::format(false, 401, 'Unauthorized: Please log in');
    }


    $services = Services::with(['additionalServices','ratings.user' => function ($query) {
        $query->select('id', 'name', 'avatar');
    }])
        ->where('user_id', $user->id)
        ->get();
    return ApiResponse::format(true, 200, 'Services retrieved successfully', $services);
}

public function store(Request $request)
{
    DB::beginTransaction();

    try {
        $user = Auth::user();
        if (!$user) {
            return ApiResponse::format(false, 401, 'Unauthorized: Please log in');
        }
        $validator = Validator::make($request->all(), [
        'category_id' => 'required|integer',
        'service_name' => 'required|string|max:255',
        'service_details' => 'required|string',
        'price' => 'required|numeric',
        'service_images' => 'nullable|array',
        'service_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'duration' => 'nullable|string|max:255',
        'location' => 'required|string|max:255',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
        'additional_services' => 'nullable|array',
        'additional_services.*.name' => 'required|string|max:255',
        'additional_services.*.price' => 'required|numeric',
        'additional_services.*.details' => 'nullable|string',
        'additional_services.*.image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // dd($request->all());
        if ($validator->fails()) {
            return ApiResponse::format(false, 400, 'Validation failed', $validator->errors());
        }
        // dd($request->all());
        $serviceImagePaths = [];
        if ($request->hasFile('service_images')) {
            foreach ($request->file('service_images') as $image) {
                $path = $image->store('public/services');
                $serviceImagePaths[] = str_replace('public/', 'storage/', $path);
            }
        }
// dd($serviceImagePaths);
        $service = Services::create([
            'user_id' => $user->id,
            'category_id' => $request->category_id,
            'service_name' => $request->service_name,
            'service_details' => $request->service_details,
            'price' => $request->price,
            'service_images' => $serviceImagePaths,
            'duration' => $request->duration,
            'location' => $request->location,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude
        ]);

        // dd($service);

        $additionalServices = [];
        if ($request->has('additional_services')) {
            foreach ($request->additional_services as $key => $item) {
                $additionalImagePath = null;
                if (isset($item['image']) && $request->hasFile("additional_services.$key.image")) {
                    $path = $request->file("additional_services.$key.image")->store('public/additional_services');
                    $additionalImagePath = str_replace('public/', 'storage/', $path);
                }
                // dd($additionalImagePath);
                $additionalServices[] = AdditionalService::create([
                    'service_id' => $service->id,
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'details' => $item['details'],
                    'images' => $additionalImagePath,
                ]);
                // dd($additionalServices);
            }
        }


        DB::commit();

        return ApiResponse::format(true, 201, 'Service created successfully', [
            'service' => $service,
            'additional_services' => $additionalServices
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return ApiResponse::format(false, 500, 'Error creating service', [
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ]);
    }
}

    public function show($id)
    {
        $user = Auth::user();
        if (!$user) {
            return ApiResponse::format(false, 401, 'Unauthorized: Please log in');
        }
        $service = Services::with(['user','additionalServices'])
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->first();
        if (!$service) {
            return ApiResponse::format(false, 404, 'Service not found');
        }
        return ApiResponse::format(true, 200, 'Service retrieved successfully', $service);
    }


    public function additionalServices()
    {
        $user = Auth::user();
        if (!$user) {
            return ApiResponse::format(false, 401, 'Unauthorized: Please log in');
        }
        $additionalServices = AdditionalService::whereHas('service', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();
        return ApiResponse::format(true, 200, 'Additional services retrieved successfully', $additionalServices);
    }
}
