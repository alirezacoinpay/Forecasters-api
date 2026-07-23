<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasFile
{
    public function image(): Attribute
    {
        return Attribute::make(
            get: fn (string | null $value) => $value ? config('app.filesUrl').self::FILE_PATH.'/'.$value : null,
        );
    }

    public function file(): Attribute
    {
        return Attribute::make(
            get: fn (string | null $value) => $value ? config('app.filesUrl').self::FILE_PATH.'/'.$value : null,
        );
    }
    public function avatar(): Attribute
    {
        return Attribute::make(
            get: fn (string | null $value) => $value ? $this->makeAvatarUrl($value) : null,
        );
    }

    public function path(): Attribute
    {
        return Attribute::make(
            get: fn (string | null $value) => $value ? config('app.filesUrl').self::FILE_PATH.'/'.$value : null,
        );
    }

    private function makeAvatarUrl($value): string
    {
        if ($this->isTelegramAvatar($value)){
            return $value;
        }
       return config('app.filesUrl').self::FILE_PATH.'/'.$value;
    }

    private function isTelegramAvatar($value)
    {
        return str_contains($value, 'https://t.me');
    }
}
