<?php

namespace App\Services\Telegram;

class TelegramAuthService
{
    public function validate(string $initData): array|false
    {
        parse_str($initData, $data);

        if (!isset($data['hash'])) {
            return false;
        }

        $receivedHash = $data['hash'];
        unset($data['hash']);

        ksort($data);

        $dataCheckString = collect($data)
            ->map(fn ($value, $key) => "{$key}={$value}")
            ->implode("\n");

        $secretKey = hash_hmac(
            'sha256',
            config('services.telegram.bot_token'),
            'WebAppData',
            true
        );

        $calculatedHash = hash_hmac(
            'sha256',
            $dataCheckString,
            $secretKey
        );

        if (!hash_equals($receivedHash, $calculatedHash)) {
            return false;
        }

        // Reject old auth data (24 hours)
        if (time() - (int) $data['auth_date'] > 86400) {
            return false;
        }

        if (!isset($data['user'])) {
            return false;
        }

        $data['user'] = json_decode($data['user'], true);

        return $data;
    }
}
