<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiUserNotificationController extends Controller
{
    public function notification(Request $request)
    {
        $user = Auth::user();

        $notifications = $user->notifications->filter(function ($notification) use ($user) {
            return isset($notification->data['user_id']) && $notification->data['user_id'] == $user->id;
        })->map(function ($notification) {
            // Fetch the booking details if booking_id exists
            $booking = isset($notification->data['booking_id'])
                ? Booking::where('id', $notification->data['booking_id'])->first()
                : null;

            return [
                'id' => $notification->id,
                'message' => $notification->data['message'],
                'booking_status' => $booking ? $booking->status : 'Not Found',
                'booking_date' => $booking ? $booking->booking_date : null,
                'booking_time' => $booking ? $booking->booking_time : null,
                'total_price' => $booking ? $booking->total_price : null,
                'read_at' => $notification->read_at,
                'created_at' => $notification->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'status' => 200,
            'message' => 'Notifications retrieved successfully',
            'data' => $notifications
        ]);
    }

    public function markRead($id){
        $user = Auth::user();
    $notification = $user->notifications()->find($id);

    if ($notification) {
        $notification->markAsRead();

        // Fetch booking service details
        $bookingService = null;
        if (isset($notification->data['booking_id'])) {
            $bookingService = Booking::with('service')->where('id', $notification->data['booking_id'])->first();
        }

        return response()->json([
            'message' => 'Notification marked as read',
            'booking_service' => $bookingService
        ]);
    }
    return response()->json(['message' => 'Notification not found'], 404);
}


public function markascompleted($id){
    $user = Auth::user();
    $notification = $user->notifications()->find($id);

    $statusconfirm = Booking::where('id', $notification->data['booking_id'])->update(['status' => 'completed']);

   return response()->json([
       'message' => 'Your service has been completed',

   ])
   ;
}

}
