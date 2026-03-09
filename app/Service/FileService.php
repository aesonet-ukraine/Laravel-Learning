<?php

namespace App\Service;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService implements Contract\FileServiceContract
{
    /**
     * {@inheritDoc}
     */
    public function upload(UploadedFile $uploadedFile, string $path = ''): string
    {
        $fileName = Str::slug(microtime()).'-'.$uploadedFile->getClientOriginalName();
        $filePath = (! empty($path) ? "$path/" : '')."$fileName";

        Storage::put($filePath, File::get($uploadedFile));
        Storage::setVisibility($filePath, 'public');

        return $filePath;
    }
}
