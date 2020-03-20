<?php

namespace Pingu\Media;

use Illuminate\Database\QueryException;
use Illuminate\Http\UploadedFile;
use Pingu\Media\Entities\ImageStyle;
use Pingu\Media\Entities\Media as MediaModel;
use Pingu\Media\Entities\MediaType;
use Pingu\Media\Exceptions\MediaTransformerException;
use Pingu\Media\Exceptions\MediaTypeException;

class Media
{
    protected $transformers = [];

    public function registerTransformer(string $class)
    {
        if(!$this->isTransformerRegistered($class::getSlug())) {
            $this->transformers[$class::getSlug()] = $class;
        }
        else{
            throw MediaTransformerException::registered($class::getSlug(), $this->transformers[$class::getSlug()]);
        }
    }

    public function isTransformerRegistered(string $slug)
    {
        return isset($this->transformers[$slug]);
    }

    public function getTransformer(string $slug)
    {
        if($this->isTransformerRegistered($slug)) {
            return $this->transformers[$slug];
        }
        throw MediaTransformerException::notRegistered($class);
    }

    public function getTransformers()
    {
        return $this->transformers;
    }

    public function getDefaultDisk()
    {
        return config('media.defaultDisk');
    }

    public function getMediaTypeForExtension(string $extension)
    {
        $type = MediaType::getByExtension($extension);
        if(is_null($type)) {
            throw MediaTypeException::extensionNotDefined($extension);
        }
        return $type;
    }

    public function uploadFile(UploadedFile $file, ?string $disk = null)
    {
        if (is_null($disk)) {
            $disk = $this->getDefaultDisk();
        }
        $diskInstance = \Storage::disk($disk);
        $ext = $file->guessExtension();
        $type = $this->getMediaTypeForExtension($ext);
        $originalFileName = $file->getClientOriginalName();
        $originalName = rtrim($originalFileName, '.'.$ext);
        $fileName = MediaModel::generateUniqueFileName($originalFileName);
        $diskInstance->putFileAs(config('media.folder'), $file, $fileName);
        try{
            $media = new MediaModel(
                [
                'name' => $originalName,
                'filename' => $fileName,
                'disk' => $disk,
                'size' => $file->getSize(),
                ]
            );
            $media->media_type()->associate($type);
            $media->save();
        }
        catch(\Exception $e){
            throw $e;
            
            $diskInstance->delete(config('media.folder').'/'.$fileName);
        }
        return $media;
    }

    public function getAvailableFileExtensions(?MediaType $ignore = null)
    {
        $out = [];
        foreach (MediaType::all() as $media){
            if (!is_null($ignore) and $media == $ignore) { 
                continue;
            }
            $out = array_merge($out, $media->extensions);
        }
        return $out;
    }
}