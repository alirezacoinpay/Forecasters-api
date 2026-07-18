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
            get: fn (string | null $value) => $value ? config('app.filesUrl').self::FILE_PATH.'/'.$value : null,
        );
    }

    public function path(): Attribute
    {
        return Attribute::make(
            get: fn (string | null $value) => $value ? config('app.filesUrl').self::FILE_PATH.'/'.$value : null,
        );
    }
}
