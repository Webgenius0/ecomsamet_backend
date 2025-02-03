<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class TwilioService
{
    protected $twilio;

    public function __construct()
    {
        $this->twilio = new Client(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'));
    }

    public function sendOtp($phoneNumber)
    {
        try {
            $verification = $this->twilio->verify->v2->services(env('TWILIO_VERIFY_SERVICE_SID'))
                ->verifications

                ->create($phoneNumber, 'sms');
                Log::info('OTP sent to ' . $phoneNumber, ['sid' => $verification->sid]);

            Log::info('OTP sent to ' . $phoneNumber, ['sid' => $verification->sid]);
            return true; // You can return any other data if needed

        } catch (\Exception $e) {
            Log::error('Twilio Send OTP Error for phone ' . $phoneNumber . ': ' . $e->getMessage());
            return false; // Or any other message to indicate failure
        }
    }

    public function verifyOtp($phoneNumber, $otp)
    {
        try {
            $verificationCheck = $this->twilio->verify->v2->services(env('TWILIO_VERIFY_SERVICE_SID'))
                ->verificationChecks
                ->create([
                    'to' => $phoneNumber,
                    'code' => $otp,
                ]);

            Log::info('OTP verification status for phone ' . $phoneNumber, ['status' => $verificationCheck->status]);

            return $verificationCheck->status === 'approved';
        } catch (\Exception $e) {
            Log::error('Twilio Verify OTP Error for phone ' . $phoneNumber . ': ' . $e->getMessage());
            return false;
        }
    }
}
