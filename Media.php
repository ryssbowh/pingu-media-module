<?php

namespace Pingu\Media;

use Illuminate\Database\QueryException;
use Illuminate\Http\UploadedFile;
use Pingu\Media\Contracts\MediaContract;
use Pingu\Media\Entities\File;
use Pingu\Media\Entities\Image;
use Pingu\Media\Entities\ImageStyle;
use Pingu\Media\Entities\Media as MediaModel;
use Pingu\Media\Entities\MediaType;
use Pingu\Media\Exceptions\MediaTransformerException;
use Pingu\Media\Exceptions\MediaTypeException;

class Media
{
    protected $transformers = [];

    /**
     * Registers a image transformer
     * 
     * @param string $class
     *
     * @throws MediaTransformerException
     */
    public function registerTransformer(string $class)
    {
        if(!$this->isTransformerRegistered($class::getSlug())) {
            $this->transformers[$class::getSlug()] = $class;
        }
        else{
            throw MediaTransformerException::registered($class::getSlug(), $this->transformers[$class::getSlug()]);
        }
    }

    /**
     * Is a tranformer registered
     * 
     * @param string  $slug
     * 
     * @return boolean
     */
    public function isTransformerRegistered(string $slug)
    {
        return isset($this->transformers[$slug]);
    }

    /**
     * Get a registered transformer
     * 
     * @param string $slug
     * 
     * @return string
     */
    public function getTransformer(string $slug): string
    {
        if($this->isTransformerRegistered($slug)) {
            return $this->transformers[$slug];
        }
        throw MediaTransformerException::notRegistered($class);
    }

    /**
     * Get all registered transformers
     * 
     * @return array
     */
    public function getTransformers(): array
    {
        return $this->transformers;
    }

    /**
     * Default disk to upload medias
     * 
     * @return string
     */
    public function getDefaultDisk(): string
    {
        return config('media.defaultDisk');
    }

    /**
     * Resolve a media type from an extension
     * 
     * @param string $extension
     * 
     * @return MediaType
     */
    public function getMediaTypeForExtension(string $extension): MediaType
    {
        $extension = trim($extension, '.');
        $type = MediaType::getByExtension($extension);
        if (is_null($type)) {
            throw MediaTypeException::extensionNotDefined($extension);
        }
        return $type;
    }

    /**
     * Guess media class (Image or File)
     * 
     * @param UploadedFile $file
     * 
     * @return string
     */
    protected function getMediaClass(UploadedFile $file): string
    {
        $mime = explode('/', $file->getMimeType());
        if (($mime[0] ?? '') == 'image') {
            return Image::class;
        }
        return File::class;
    }

    /**
     * Uploads a file to a disk
     * 
     * @param UploadedFile $file
     * @param string|null  $disk
     * 
     * @return MediaContract
     */
    public function uploadFile(UploadedFile $file, ?string $disk = null): MediaContract
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
        $mediaClass = $this->getMediaClass($file);
        try{
            $mediaInstance = $mediaClass::createFromUploadedFile($file);
            $media = new MediaModel(
                [
                'name' => $originalName,
                'filename' => $fileName,
                'disk' => $disk,
                'size' => $file->getSize(),
                ]
            );
            $media->media_type()->associate($type);
            $media->instance()->associate($mediaInstance);
            $media->save();
        }
        catch(\Exception $e){
            $diskInstance->delete(config('media.folder').'/'.$fileName);
            throw $e;
        }
        return $mediaInstance;
    }

    public function getAvailableFileExtensions(?MediaType $ignore = null)
    {
        $out = [];
        foreach (MediaType::all() as $media) {
            if (!is_null($ignore) and $media == $ignore) { 
                continue;
            }
            $out = array_merge($out, $media->extensions);
        }
        return $out;
    }
}