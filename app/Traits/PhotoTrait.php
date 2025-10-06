<?php

namespace App\Traits;

use Buglinjo\LaravelWebp\Exceptions\CwebpShellExecutionFailed;
use Buglinjo\LaravelWebp\Exceptions\DriverIsNotSupportedException;
use Buglinjo\LaravelWebp\Exceptions\ImageMimeNotSupportedException;
use Buglinjo\LaravelWebp\Webp;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

trait PhotoTrait
{
    /**
     * @throws CwebpShellExecutionFailed
     * @throws ImageMimeNotSupportedException
     * @throws DriverIsNotSupportedException
     */


    function saveImage(UploadedFile $file, string $folder, string $type = 'image')
    {
        Storage::disk('public')->makeDirectory($folder);

        $extension = $file->getClientOriginalExtension();
        $file_name = uniqid() . time() . '.' . $extension;
        $path = $folder . '/' . $file_name;

        $file->move(storage_path('app/public/' . $folder), $file_name);

        return 'storage/' . $path;
    }


}