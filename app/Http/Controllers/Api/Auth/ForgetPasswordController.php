<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\PasswordReset;
use Carbon\Carbon;  // Import the Log facade
use App\Models\ApiUser;

class ForgetPasswordController extends Controller
{
    public function requestReset(Request $request)
    {
        // Validate the email
        $request->validate([
            'email' => 'required|email|exists:api_users,email',
        ]);

        $email = $request->input('email');
        $user = ApiUser::where('email', $email)->first();

        // Generate a 6-digit OTP
        $otp = rand(100000, 999999);

        // Store OTP in the PasswordReset table
        PasswordReset::updateOrCreate(
            ['email' => $email],
            ['otp' => $otp, 'created_at' => Carbon::now()]
        );

        // Send OTP email
        Mail::to($email)->send(new \App\Mail\OtpMail($otp));

        return response()->json(['message' => 'OTP sent to your email address.'], 200);
    }


    public function resetPassword(Request $request)
    {
        // Validate the input
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:4',
            'password' => 'required|confirmed|min:8',
        ]);

        $reset = PasswordReset::where('email', $request->email)
            ->where('otp', $request->otp)
            ->first();

        if (!$reset || Carbon::parse($reset->created_at)->addMinutes(10)->isPast()) {
            return response()->json(['message' => 'OTP is invalid or expired.'], 400);
        }

        // Reset the password
        $user = ApiUser::where('email', $request->email)->first();
        $user->password = bcrypt($request->password);
        $user->save();

        // Delete the OTP record
        $reset->delete();

        return response()->json(['message' => 'Password has been reset successfully.'], 200);
    }
}
