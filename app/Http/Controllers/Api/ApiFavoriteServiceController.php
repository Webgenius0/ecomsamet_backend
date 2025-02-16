<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\ApiUser;
use App\Models\Favorite;
use App\Models\Services;
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
                return ApiResponse::format(false, 'User not authenticated', null, 401);
            }

            $favorites = $user->favoritedServices()->get();

            return ApiResponse::format(true, 'Favorited services retrieved successfully', $favorites);
        } catch (\Exception $e) {
            return ApiResponse::format(false, 'An error occurred', $e->getMessage(), 500);
        }
    }

    /**
     * Toggle favorite status for a service.
     */
    public function toggle($service_id)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return ApiResponse::format(false, 'User not authenticated', null, 401);
            }

            // Ensure the service exists
            $service = Services::find($service_id);
            if (!$service) {
                return ApiResponse::format(false, 'Service not found', null, 404);
            }

            // Check if service is already favorited
            $favorite = Favorite::where('user_id', $user->id)
                                ->where('service_id', $service->id)
                                ->first();

            if ($favorite) {
                // Remove from favorites
                $favorite->delete();
                return ApiResponse::format(true, 'Removed from favorites', ['status' => false]);
            } else {
                // Add to favorites
                Favorite::create([
                    'user_id' => $user->id,
                    'service_id' => $service->id,
                    'status' => 'true'
                ]);
                return ApiResponse::format(true, 'Added to favorites', ['status' => true]);
            }
        } catch (QueryException $qe) {
            return ApiResponse::format(false, 'Database error', $qe->getMessage(), 500);
        } catch (\Exception $e) {
            return ApiResponse::format(false, 'An error occurred', $e->getMessage(), 500);
        }
    }

}
