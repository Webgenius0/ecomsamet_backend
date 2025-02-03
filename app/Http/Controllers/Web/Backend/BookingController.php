<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\ApiUser;
use App\Models\Services;

class BookingController extends Controller
{
    public function index()
    {
        // Show all bookings also services data and user data that is in the backend
        // You can use eager loading or query builder to fetch related data
        // $bookings = Booking::with(['services', 'user'])->get();
        // Or you can use query builder
         $bookings = Booking::with(['service', 'apiuser'])->get();

        return view('backend.layouts.booking.index',compact('bookings'));
    }
}
