<?php

namespace Pingu\Media\Contracts;

use Illuminate\Http\UploadedFile;

interface UploadsFiles
{
    public function uploadFile(UploadedFile $file);
}