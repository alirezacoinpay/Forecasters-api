<?php

namespace App\Helpers;

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
}
