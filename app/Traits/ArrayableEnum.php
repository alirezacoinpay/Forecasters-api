<?php

namespace App\Traits;

trait ArrayableEnum
{
    public static function toArray(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
    public static function toFormArray(): array
    {
        $data = [];
        foreach (self::cases() as $case) {
            $data[$case->value] = $case->name;
        }
        return $data;
    }

    public static function find(string $value): string | null
    {
        if (self::tryFrom($value)){
            return strtolower(self::from($value)->name);
        }
        return  null;
    }
}
