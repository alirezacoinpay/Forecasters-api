<?php

namespace App\Services\Sms\Providers;

use App\Enums\SMSTemplate;
use App\Services\Sms\SmsServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Translation\Provider\ProviderInterface;

class SmsIrProvider implements SmsServiceInterface
{

    protected string $url = 'https://api.sms.ir/v1/';

    protected string $token = 'RmaI9a1S21640lHLF0ELklof4F2cdC9pe8xjvk8kNCb1WlCs';
    protected array $templates = [
        SMSTemplate::LOGIN->value => '436677'
    ];
    protected string $lineNumber = '30007487131873';
    public function sendSms(array $mobiles,string $message): bool
    {
        try {
            $response = Http::withHeaders([
                'X-API-KEY' => $this->token,
                'Accept' => 'application/json',
            ])->withoutVerifying()->post( $this->url.'send/bulk', [

                'lineNumber' => $this->lineNumber,
                'messageText' => $message,
                'mobiles' => $mobiles,
            ]);

            return $this->handleResponse($response);
        }catch (\Exception $exception){

            Log::error($exception->getMessage());
            return false;
        }
    }
    public function sendOTP(string $mobile, string $otp, string $template): bool
    {
        $templateNumber = $this->getTemplate($template);
        try {
            $response = Http::withHeaders([
                'X-API-KEY' => $this->token,
                'Accept' => 'application/json',
            ])->withoutVerifying()->post( $this->url.'send/verify', [

                'mobile' => $mobile,
                'templateId' => $templateNumber,
                'parameters' => [
                    [
                        'name' => 'CODE',
                        'value' => $otp
                    ]
                ],
            ]);

            return $this->handleResponse($response);
        }catch (\Exception $exception){

            Log::error($exception->getMessage());
            return false;
        }
    }
    private function handleResponse($response): bool
    {
        try {
            if ($response->successful()) {

                $data = $response->json();
                Log::error('MESSAGE SENT Data:' .$data['message']);
                return true;

            } else {
                $statusCode = $response->status();
                $errorBody = $response->body();

                Log::error('MESSAGE error code: ' . $statusCode);
                Log::error('MESSAGE error text: ' . $errorBody);
                return false;
            }
        }catch (\Exception $exception){

            Log::error($exception->getMessage());
            return false;
        }
    }
    private function getTemplate($template): string
    {
        return $this->templates[$template];
    }
}
