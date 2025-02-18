<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HairHomeController extends Controller
{
    public function weeklyPayment() {

        $user = Auth::user();
        $startOfWeek = Carbon::now()->startOfWeek();

        $bookings = Booking::with(['service', 'service.user'])
            ->whereHas('service', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('bookings.booking_date', '>=', $startOfWeek)
            ->leftJoin('booking_additional_services', 'bookings.id', '=', 'booking_additional_services.booking_id')
            ->selectRaw('
                bookings.id,
                bookings.total_price,
                COALESCE(SUM(booking_additional_services.price), 0) as additional_price,
                (bookings.total_price + COALESCE(SUM(booking_additional_services.price), 0)) as total_payment,
                bookings.created_at
            ')
            ->groupBy('bookings.id', 'bookings.total_price', 'bookings.created_at')
            ->get();

        $totalWeeklyEarnings = $bookings->sum('total_payment');


        return response()->json([
            'weekly_earnings' => $totalWeeklyEarnings,
            'bookings' => $bookings->makeHidden(['service']),
        ]);
    }


    public function monthlyPayment(){

        $user = Auth::user();
        $startOfMonth = Carbon::now()->startOfMonth();

        $bookings = Booking::with(['service', 'service.user'])
            ->whereHas('service', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('bookings.booking_date', '>=', $startOfMonth)
            ->leftJoin('booking_additional_services', 'bookings.id', '=', 'booking_additional_services.booking_id')
            ->selectRaw('
                bookings.id,
                bookings.total_price,
                COALESCE(SUM(booking_additional_services.price), 0) as additional_price,
                (bookings.total_price + COALESCE(SUM(booking_additional_services.price), 0)) as total_payment,
                bookings.created_at
            ')
            ->groupBy('bookings.id', 'bookings.total_price', 'bookings.created_at')
            ->get();

        $totalMonthlyEarnings = $bookings->sum('total_payment');

        return response()->json([
            'monthly_earnings' => $totalMonthlyEarnings,
            'bookings' => $bookings->makeHidden(['service']),
        ]);
    }

    public function yearlyPayment(){

        $user = Auth::user();
        $startOfYear = Carbon::now()->startOfYear();

        $bookings = Booking::with(['service', 'service.user'])
            ->whereHas('service', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('bookings.booking_date', '>=', $startOfYear)
            ->leftJoin('booking_additional_services', 'bookings.id', '=', 'booking_additional_services.booking_id')
            ->selectRaw('
                bookings.id,
                bookings.total_price,
                COALESCE(SUM(booking_additional_services.price), 0) as additional_price,
                (bookings.total_price + COALESCE(SUM(booking_additional_services.price), 0)) as total_payment,
                bookings.created_at
            ')
            ->groupBy('bookings.id', 'bookings.total_price', 'bookings.created_at')
            ->get();

        $totalYearlyEarnings = $bookings->sum('total_payment');

        return response()->json([
            'yearly_earnings' => $totalYearlyEarnings,
            'bookings' => $bookings->makeHidden(['service']),
        ]);
    }
}
