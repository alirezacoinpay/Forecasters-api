<?php

namespace App\Services\Sms;

interface SmsServiceInterface
{
    public function sendSms(array $mobiles,string $message);
    public function sendOTP(string $mobile, string $otp, string $template);
}
