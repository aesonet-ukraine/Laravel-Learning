<?php

namespace App\Service\Contract;

use Illuminate\Http\UploadedFile;

interface FileServiceContract
{
    public function upload(UploadedFile $uploadedFile, string $path = ''): string;
}
