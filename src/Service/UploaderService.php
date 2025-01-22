<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderService
{

    public function uploadFile(UploadedFile $file, string $publicFolder): string
    {

        /**
         * @var UploadedFile $file
         */

        $extension = $file->getClientOriginalExtension();
        $fileName = bin2hex(random_bytes(4)) . '_' . uniqid() . '.' . $extension;

        $file->move($publicFolder, $fileName);
        return $fileName;
    }
}
