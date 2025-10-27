<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Morilog\Jalali\Jalalian;

class DateHelper
{
    /**
     * @throws \Exception
     */
    public static function toJalali($date): string
    {
        try {

            $jalaliDate = Jalalian::fromDateTime($date);

            return $jalaliDate->format('Y/m/d H:i:s');
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }
    }

    public static function shortTimeAgo($dateTime): string
    {
        $created = Carbon::parse($dateTime);
        $now = Carbon::now();
        $diffInSeconds = (integer) $created->diffInSeconds($now);

        if ($diffInSeconds < 60) {
            return $diffInSeconds . 's';
        }

        $diffInMinutes = (integer) $created->diffInMinutes($now);
        if ($diffInMinutes < 60) {
            return $diffInMinutes . 'm';
        }

        $diffInHours = (integer) $created->diffInHours($now);
        if ($diffInHours < 24) {
            return $diffInHours . 'h';
        }

        $diffInDays = (integer) $created->diffInDays($now);
        if ($diffInDays < 30) {
            return $diffInDays . 'd';
        }

        $diffInMonths = (integer) $created->diffInMonths($now);
        if ($diffInMonths < 12) {
            return $diffInMonths . 'mo';
        }

        $diffInYears = (integer) $created->diffInYears($now);
        return $diffInYears . 'y';
    }
}
