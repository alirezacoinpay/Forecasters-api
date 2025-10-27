<?php
namespace App\Services\Sms;
use InvalidArgumentException;

class SmsService implements SmsServiceInterface
{
    public function __construct(
        protected SmsServiceInterface $provider
    )
    {

    }

    public function setProvider(string|SmsServiceInterface $provider): self
    {
        if ($provider instanceof SmsServiceInterface) {
            $this->provider = $provider;
            return $this;
        }

        $class = $this->providerMap[$provider] ?? $provider;

        if (!class_exists($class)) {
            throw new InvalidArgumentException(
                sprintf("Unknown provider '%s' and class '%s' not found.", $provider, $class)
            );
        }

        $instance = app($class);

        if (!$instance instanceof SmsServiceInterface) {
            throw new InvalidArgumentException(
                sprintf("Class %s must implement %s.", $class, SmsServiceInterface::class)
            );
        }

        $this->provider = $instance;
        return $this;
    }

    public function sendOTP($mobile, $otp , string $template)
    {
        return $this->provider->sendOTP($mobile, $otp , $template);
    }

    public function sendSms($mobiles, $message)
    {
        return $this->provider->sendSms($mobiles, $message);
    }
}
