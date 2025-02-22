<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\ApiUser;
use App\Models\Favorite;
use App\Models\Services;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;

class ApiFavoriteServiceController extends Controller
{
    /**
     * Get all favorited services for the authenticated user.
     */
    public function index()
{
    try {
        $user = Auth::user();

        if (!$user) {
            return ApiResponse::format(false, 401, 'User not authenticated', null);
        }

        $favorites = $user->favoritedServices()->get();

        return ApiResponse::format(true, 200, 'Favorited services retrieved successfully', $favorites);
    } catch (\Exception $e) {
        return ApiResponse::format(false, 500, 'An error occurred', $e->getMessage());
    }
}

    /**
     * Toggle favorite status for a service.
     */
    public function toggle($id)
{
    try {
        $user = Auth::user();

        if (!$user) {
            return ApiResponse::format(false, 401, 'User not authenticated');
        }
        $service = Services::findOrFail($id);


        if (!User::find($user->id)) {
            return ApiResponse::format(false, 404, 'User not found');
        }
        if (!$service) {
            return ApiResponse::format(false, 404, 'Service not found');
        }

        $favorite = Favorite::where('user_id', $user->id)
                            ->where('service_id', $service->id)
                            ->first();

        if ($favorite) {
            // Remove from favorites
            $favorite->delete();
            //custom the response
            return response()->json([
                'code' => 201,
               'message' => 'Removed from favorites',
               'status' => false,
            ], 200);
        } else {
            // Add to favorites
            Favorite::create([
                'user_id' => $user->id,
                'service_id' => $service->id,
                'status' => true
            ]);
            return response()->json([
                'code' => 201,
               'message' => 'Added to favorites',
               'status' => true,
            ], 200);
        }
    } catch (QueryException $qe) {
        return ApiResponse::format(false, 500, 'Database error: ' . $qe->getMessage());
    } catch (\Exception $e) {
        return ApiResponse::format(false, 500, 'An error occurred: ' . $e->getMessage());
    }
}



}
