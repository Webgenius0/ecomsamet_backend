<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ApiBookingController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::guard('api')->user(); // Authenticate using 'api' guard if needed

            if (!$user) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $bookings = Booking::where('user_id', $user->id)
                ->with('apiuser', 'service')
                ->get();

            return response()->json($bookings, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve bookings.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function store(Request $request)
    {
        try {
            $user = Auth::guard('api')->user(); // Authenticate the user

            if (!$user) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $validatedData = $request->validate([
                'service_id' => 'required|exists:services,id',
                'date' => 'required|date',
                'time' => 'required|string',
                'status' => 'required|string',
            ]);

            $booking = new Booking();
            $booking->user_id = $user->id; // Use authenticated user's ID
            $booking->service_id = $validatedData['service_id'];
            $booking->date = $validatedData['date'];
            $booking->time = $validatedData['time'];
            $booking->status = $validatedData['status'];
            $booking->save();

            return response()->json([
                'message' => 'Booking created successfully',
                'booking' => $booking,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create booking.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function show($id)
    {
        try {
            $user = Auth::guard('api')->user(); // Authenticate the user

            if (!$user) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            $booking = Booking::where('id', $id)->where('user_id', $user->id)->first();

            if (!$booking) {
                return response()->json(['message' => 'Booking not found or access denied'], 403);
            }

            return response()->json($booking, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve booking.',
                'error' => $e->getMessage(),
            ], 500);
        }
        
    }


    public function destroy($id)
{
    try {
        $user = Auth::guard('api')->user(); // Authenticate the user

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $booking = Booking::where('id', $id)->where('user_id', $user->id)->first();

        if (!$booking) {
            return response()->json(['message' => 'Booking not found or access denied'], 403);
        }

        $booking->delete();

        return response()->json(['message' => 'Booking deleted successfully'], 200);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to delete booking.',
            'error' => $e->getMessage(),
        ], 500);
    }
}

}
