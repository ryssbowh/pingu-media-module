<?php

namespace Pingu\Media\Entities;

use Illuminate\Http\File;
use Pingu\Core\Traits\Models\HasMachineName;
use Pingu\Entity\Entities\Entity;
use Pingu\Media\Entities\Media;
use Pingu\Media\Entities\MediaTransformer;
use Pingu\Media\Entities\Policies\ImageStylePolicy;
use Pingu\Media\Exceptions\MediaStyleException;

class ImageStyle extends Entity
{
    use HasMachineName;

    protected $fillable = ['name', 'machineName', 'description'];

    protected $attributes = [
        'description' => '',
    ];

    public $adminListFields = ['name', 'description'];

    public static function boot()
    {
        parent::boot();

        static::deleted(function ($style) {
            $style->deleteImages();
        });
    }

    public function getPolicy(): string
    {
        return ImageStylePolicy::class;
    }

    /**
     * Transformations relationship
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transformations()
    {
        return $this->hasMany(MediaTransformer::class)->orderBy('weight');
    }

    /**
     * Media relationship
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function medias()
    {
        return $this->belongsToMany(Media::class)->withTimestamps();
    }

    /**
     * Gets and instancie all transformations for that style
     * 
     * @return array;
     */
    public function getTransformations()
    {
        return $this->transformations->map(function ($transformation) {
            return $transformation->instance();
        });
    }

    /**
     * Returns the pivot of the relationship with a media
     * 
     * @param  Media  $media
     * @return Illuminate\Database\Eloquent\Relations\Pivot
     */
    protected function getPivotWithMedia(Media $media)
    {
        return $media->image_styles()->wherePivot('image_style_id', '=', $this->id)->get()->first()->pivot;
    }

    /**
     * Does this style exist for a media
     * 
     * @param  Media  $media
     * @return bool
     */
    public function existsForMedia(Media $media)
    {
        return $media->image_styles->contains($this);
    }

    /**
     * Image path for this style and a media
     * 
     * @param  Media  $media
     * @return string
     */
    protected function imagePath(Media $media)
    {
        return $media->getFolder().'/'.$this->getFolder().'/'.$media->filename;
    }

    /**
     * Get folder for this style
     * 
     * @return string
     */
    public function getFolder()
    {
        return str_plural($this->machineName);
    }

    /**
     * Does the image exists for this style and a media
     * 
     * @param  Media  $media
     * @return bool
     */
    public function fileExists(Media $media)
    {
        return $media->getDisk()->exists($this->imagePath($media));
    }

    /**
     * deletes all images associated with this style
     */
    public function deleteImages()
    {
        foreach ($this->medias as $media) {
            $this->deleteImage($media);
        }
    }

    /**
     * Deletes an image for a media
     * 
     * @param Media  $media
     */
    public function deleteImage(Media $media)
    {
        $path = $this->imagePath($media);
        $media->getDisk()->delete($path);
    }

    /**
     * Has this style image being generated before this style was updated
     * 
     * @param  Media   $media
     * @return boolean
     */
    protected function isOutdated(Media $media)
    {
        $thisTime = $this->updated_at->timestamp;
        $mediaTime = $this->getPivotWithMedia($media)->updated_at->timestamp;

        return ($thisTime > $mediaTime);
    }

    /**
     * Url for this style for a media.
     * Will create the image if it doesn't exist
     * 
     * @param  Media  $media
     * @return string
     */
    public function url(Media $media)
    {
        if(!$this->existsForMedia($media) or $this->isOutdated($media)){
            $this->createImage($media);
        }
        return $media->getDisk()->url($this->imagePath($media));
    }

    /**
     * Apply the transformations to a file
     * 
     * @param  string $file
     */
    public function applyTransformations(string $file)
    {
        foreach($this->getTransformations() as $transformation){
            $transformation->process($file);
        }
    }

    /**
     * Create an image for this style and a media.
     * Returns the relative path of the created image
     * 
     * @param  Media  $media
     * @return string|null
     */
    public function createImage(Media $media)
    {
        if(!$media->fileExists()){
            return;
        }
        //move the image to the temp disk
        $tmpName = $media->copyToTemporaryDisk();
        $tmpFile = temp_path($tmpName);
        //apply transformations
        $this->applyTransformations($tmpFile);
        //move image from temp disk to destination disk
        $target = $media->getFolder().'/'.$this->getFolder();
        $media->getDisk()->putFileAs($target, new File($tmpFile), $media->filename);
        //delete image from temp disk
        \Storage::disk('tmp')->delete($tmpName);
        //attach media to image style
        $this->medias()->detach($media);
        $this->medias()->attach($media);
        $media->load('image_styles');

        return $target.'/'.$media->filename;
    }
}