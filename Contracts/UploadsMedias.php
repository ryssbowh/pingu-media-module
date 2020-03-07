<?php

namespace Pingu\Media\Contracts;

use Illuminate\Http\UploadedFile;

interface UploadsMedias
{
    /**
     * Uploads files and turn them into Medias
     * 
     * @param  UploadedFile|array $files
     * 
     * @return Media|array
     */
    public function uploadMedias($files);
}