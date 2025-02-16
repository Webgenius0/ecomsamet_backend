<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\AdditionalService;
use App\Models\Booking;
use App\Models\BookingAdditionalService;
use App\Models\Services;
use App\Models\User;
use App\Notifications\BookingNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

use function Illuminate\Log\log;

class UserBookingController extends Controller
{

    public function show($id)
    {
        try {
           $user = Auth::user(); // Authenticate the user

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

public function bookService(Request $request)
{

    try {
        DB::beginTransaction();
        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        // Validate request
        $validator = Validator::make($request->all(), [
            'service_id' => 'required|exists:services,id',
            'booking_date' => 'required|date',
            'booking_time' => 'required|string',
            'booking_tax' => 'nullable|string',
            'additional_services' => 'nullable|array',
            'additional_services.*' => 'exists:additional_services,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 400);
        }

        // Get the service
        $service = Services::findOrFail($request->service_id);
        $totalPrice = $service->price;

        // Create the booking first
        $booking = Booking::create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time,
            'total_price' => $totalPrice, // Initial total (without additional services)
            'booking_tax' => 0, // Placeholder for tax
            'status' => 'pending'
        ]);

        // Handle optional additional services
        if ($request->has('additional_services') && !empty($request->additional_services)) {
            foreach ($request->additional_services as $additionalServiceId) {
                $additionalService = AdditionalService::findOrFail($additionalServiceId);
                $totalPrice += $additionalService->price;

                // Now $booking exists, so no error will occur
                BookingAdditionalService::create([
                    'booking_id' => $booking->id,
                    'additional_service_id' => $additionalService->id,
                    'price' => $additionalService->price
                ]);
            }
        }

        // Calculate 5% tax and update booking
        $taxAmount = $totalPrice * 0.05;
        $finalAmount = $totalPrice + $taxAmount;

        $booking->update([
            'total_price' => $finalAmount,
            'booking_tax' => $taxAmount
        ]);

        //Notification to the hairdresser
        $hairdresser = User::findOrFail($service->user_id);
        // dd($hairdresser);
        if ($hairdresser) {
            $hairdresser->notify(new BookingNotification());
        }


        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Booking successful',
            'booking' => $booking
        ]);

    } catch (Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Booking failed',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function getconfirmBookings()
{
    $user = Auth::user();

    if (!$user) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }
    $bookings = Booking::where('user_id', $user->id)
                ->where('status', 'confirmed')
                ->with(['service', 'additionalServices'])
                ->orderBy('booking_date', 'desc')
                ->get();

    return ApiResponse::format(true, 200, 'Pending bookings', $bookings);
}

public function getcompeletedBookings()
{
    $user = Auth::user();

    if (!$user) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }
    $bookings = Booking::where('user_id', $user->id)
                ->where('status', 'completed')
                ->with(['service', 'additionalServices'])
                ->orderBy('booking_date', 'desc')
                ->get();

    return ApiResponse::format(true, 200, 'Completed bookings', $bookings);
}
public function getconcelledBookings()
{
    $user = Auth::user();

    if (!$user) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }
    $bookings = Booking::where('user_id', $user->id)
                ->where('status', 'cancelled')
                ->with(['service', 'additionalServices'])
                ->orderBy('booking_date', 'desc')
                ->get();

    return ApiResponse::format(true, 200, 'Cancelled bookings', $bookings);
}


}
