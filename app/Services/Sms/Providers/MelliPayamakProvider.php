<?php

namespace App\Services\Sms\Providers;

use App\Enums\SmsTemplate;
use App\Services\Sms\SmsServiceInterface;
use Illuminate\Support\Facades\Http;

class MelliPayamakProvider implements SmsServiceInterface
{
    protected string $baseUrl;
    protected string $username;
    protected string $password;
    protected string $fromNumber;
    protected string $otpLoginBodyID;

    public function __construct()
    {
        $this->baseUrl = config('services.sms.melliPayamak.baseUrl');
        $this->username = config('services.sms.melliPayamak.username');
        $this->password = config('services.sms.melliPayamak.password');
        $this->fromNumber = config('services.sms.melliPayamak.fromNumber');
        $this->otpLoginBodyID = config('services.sms.melliPayamak.otpLoginBodyID');
    }

    public function sendSms($to, $message): array
    {

        return [$to, $message];
    }

    public function sendOtp($mobile, $otp , string $template): array
    {
        switch ($template) {
            case SmsTemplate::LOGIN->value :
                $template = $this->otpLoginBodyID;
                break;
            default :
                $template = $this->otpLoginBodyID;
        }
        $url = $this->baseUrl.'SendSMS/BaseServiceNumber';
        $params = [
            'username' => $this->username,
            'password' => $this->password,
            'to' => $mobile,
            'text' => $otp,
            'bodyId' => $template,
        ];

        $response =  Http::withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
            ])
            ->withoutVerifying()
            ->post($url, $params);

        return [
            'success' => $response->successful(),
            'message' => $response->getBody(),
            'data' => [
                'status' => $response->getStatusCode(),
            ]
        ];
    }
}
