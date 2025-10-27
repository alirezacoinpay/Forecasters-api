<?php
namespace App\Providers;

use App\Services\Sms\SmsService;
use App\Services\Sms\SmsServiceInterface;
use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->app->bind(SmsServiceInterface::class, function ($app) {

            $app->make(config('services.sms.default_provider'));
        });

        $this->app->bind(SmsService::class, function ($app) {
            return new SmsService($app->make(config('services.sms.default_provider')));
        });
    }
}
