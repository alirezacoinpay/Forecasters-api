<?php

use App\Helpers\FileHelper;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
    Storage::fake('local');
});

test('uploadFile stores file in public disk', function () {
    $file = UploadedFile::fake()->image('test.jpg');
    
    $path = FileHelper::uploadFile($file, 'test');
    
    Storage::disk('public')->assertExists($path);
    expect($path)->toContain('test');
});

test('uploadPrivateFile stores file in local disk', function () {
    $file = UploadedFile::fake()->image('test.jpg');
    
    $path = FileHelper::uploadPrivateFile($file, 'test');
    
    Storage::disk('local')->assertExists($path);
    expect($path)->toContain('test');
});

test('deleteFile removes file from public disk', function () {
    Storage::disk('public')->put('test/file.jpg', 'content');
    $fileUrl = config('app.filesUrl') . '/test/file.jpg';
    
    $result = FileHelper::deleteFile($fileUrl);
    
    expect($result)->toBeTrue();
    Storage::disk('public')->assertMissing('test/file.jpg');
});

test('deleteFile returns false when file does not exist', function () {
    $fileUrl = config('app.filesUrl') . '/nonexistent/file.jpg';
    
    $result = FileHelper::deleteFile($fileUrl);
    
    expect($result)->toBeFalse();
});

