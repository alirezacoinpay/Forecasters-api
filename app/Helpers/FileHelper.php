<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class FileHelper
{
    public static function uploadFile($file, $directory): string
    {
        $filePath = Storage::disk('public')->putFile($directory, $file);
        return basename($filePath);
    }
    public static function uploadPrivateFile($file, $directory): string
    {
        return Storage::disk('local')->putFile($directory, $file);
    }

    public static function deleteFile($fileUrl): bool
    {
        $directory = str_replace(config('app.filesUrl'), '', dirname($fileUrl));

        if (Storage::disk('public')->exists($directory.'/'.basename($fileUrl))){
            return Storage::disk('public')->delete($directory.'/'.basename($fileUrl));
        }else{
            return false;
        }
    }

    public static function deletePrivateFile($fileUrl): bool
    {
        $directory = str_replace(config('app.filesUrl'), '', dirname($fileUrl));

        if (Storage::disk('local')->exists($directory.'/'.basename($fileUrl))){
            return Storage::disk('local')->delete($directory.'/'.basename($fileUrl));
        }else{
            return false;
        }
    }
}
