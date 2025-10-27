<?php

namespace App\Facades;
use App\Services\Currency\CurrencyService;
use App\Services\Sms\SmsService;
use Illuminate\Support\Facades\Facade;

/**
 *
 * @method static \App\Services\Sms\SmsService setProvider(string $providerClass)
 * @method static array sendOtp(string $mobile, string $otp, string $template)
 * @method static array sendSms(array $mobiles,string $message)
 * @see \App\Services\Sms\SmsService
 */

class Sms extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SmsService::class;
    }
}
