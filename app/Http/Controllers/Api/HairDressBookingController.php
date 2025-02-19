<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HairDressBookingController extends Controller
{
    public function penddingdata(Request $request)
    {
        $user = Auth::user();

        $bookings = Booking::with(['service', 'service.user','user'])
        ->whereHas('service', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->get();

        return $bookings;
    }

    public function acceptbooking($id, $action)
    {
        // dd($action);

        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        $user = Auth::user();
        if ($booking->service->user_id != $user->id) {
            return response()->json(['message' => 'You do not have permission to modify this booking'], 403);
        }
// dd($booking);

        if ($action === 'confirm') {
            $booking->status = 'confirmed';
            $booking->save();
            return response()->json(['message' => 'Booking accepted successfully']);
        } elseif ($action === 'cancel') {
            $booking->status = 'cancelled';
            $booking->save();
            return response()->json(['message' => 'Booking canceled successfully']);
        }
        elseif ($action === 'complete') {
            $booking->status ='completed';
            $booking->save();
            return response()->json(['message' => 'Booking compeleted successfully']);
        }

        else {
            return response()->json(['message' => 'Invalid action'], 400);
        }
    }

    public function booked(){
        {
            $user = Auth::user();

            $bookings = Booking::with(['service', 'service.user'])
                ->whereHas('service', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->where('status', 'confirmed')
                ->get();

            return $bookings;
        }
    }


    public function completed(){
        $user = Auth::user();

        $bookings = Booking::with(['service', 'service.user'])
            ->whereHas('service', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('status', 'completed')
            ->get();

        return $bookings;

    }

}
