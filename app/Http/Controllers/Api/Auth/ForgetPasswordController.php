<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\PasswordReset;
use Carbon\Carbon;
use App\Models\User;

class ForgetPasswordController extends Controller
{
    public function requestReset(Request $request)
    {
        try {

            $request->validate([
                'phone' => 'required|exists:users,phone',
            ]);

            $phone = $request->input('phone');


            $user = User::where('phone', $phone)->first();
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }
            // dd($user);

            // Generate a 6-digit OTP
            $otp = rand(1000, 9999);

            // Store OTP in the PasswordReset table
            PasswordReset::updateOrCreate(
                ['phone' => $phone],
                ['otp' => $otp, 'created_at' => Carbon::now()]
            );

// dd($user);

            return ApiResponse::format(true, 200, 'OTP sent successfully', [
                'otp'=>$otp,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function resetPassword(Request $request)
{
    // Validate the input
    $request->validate([
        'phone' => 'required|exists:users,phone',
        'otp' => 'required|digits:4',
        'password' => 'required|min:8',
    ]);
    // dd($request);

    $reset = PasswordReset::where('phone', $request->phone)
        ->where('otp', $request->otp)
        ->first();

// dd($reset);

if (!$reset || Carbon::parse($reset->created_at)->addHours(2)->isPast()) {
    return response()->json(['message' => 'OTP is invalid or expired.'], 400);
}
// dd($reset);


    $user = User::where('phone', $request->phone)->first();
    if (!$user) {
        return response()->json(['message' => 'User not found.'], 404);
    }

    $user->password = bcrypt($request->password);
    $user->save();

    $reset->delete();

    return response()->json(['message' => 'Password has been reset successfully.'], 200);
}
}
