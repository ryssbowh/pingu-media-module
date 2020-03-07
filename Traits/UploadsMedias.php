<?php 

namespace Pingu\Media\Traits;

use Illuminate\Http\UploadedFile;

trait UploadsMedias
{
    abstract protected function getDisk(): string;

    public function uploadMedias($files)
    {
        if (!is_array($files)) {
            return ($files instanceof UploadedFile) ? $this->uploadSingleMedia($files) : (int) $files;
        }
        $_this = $this;
        return array_map(function ($file) use ($_this){
            return ($file instanceof UploadedFile) ? $_this->uploadSingleMedia($file) : (int) $file;
        }, $files);
    }

    protected function uploadSingleMedia(UploadedFile $file)
    {
        return \Media::uploadFile($file, $this->getDisk())->id;
    }
}