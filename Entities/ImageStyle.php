<?php

namespace Pingu\Media\Entities;

use Illuminate\Http\File;
use Pingu\Core\Traits\Models\HasMachineName;
use Pingu\Entity\Support\Entity;
use Pingu\Media\Entities\Image;
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

        static::deleted(
            function ($style) {
                $style->deleteImages();
                \ImageStyle::forgetCache();
            }
        );

        static::created(function ($style) {
            \ImageStyle::forgetCache();
        });

        static::updated(function ($style) {
            \ImageStyle::forgetCache();
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
     * Image relationship
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function images()
    {
        return $this->belongsToMany(Image::class)->withTimestamps();
    }

    /**
     * Gets and instancie all transformations for that style
     * 
     * @return array;
     */
    public function getTransformations()
    {
        return $this->transformations->map(
            function ($transformation) {
                return $transformation->instance();
            }
        );
    }

    /**
     * Returns the pivot of the relationship with a image
     * 
     * @param Image $image
     * 
     * @return Illuminate\Database\Eloquent\Relations\Pivot
     */
    protected function getPivotWithMedia(Image $image)
    {
        return $image->image_styles->where('id', $this->id)->first()->pivot;
    }

    /**
     * Does this style exist for a image
     * 
     * @param Image $image
     * 
     * @return bool
     */
    public function existsForMedia(Image $image)
    {
        return $image->image_styles->contains($this);
    }

    /**
     * Image path for this style and a image
     * 
     * @param Image $image
     * 
     * @return string
     */
    protected function stylePath(Image $image)
    {
        return config('media.folder').'/'.$this->getFolder().'/'.$image->filename;
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
     * Does the image exists for this style and a image
     *
     * @param Image $image
     * 
     * @return bool
     */
    public function fileExists(Image $image)
    {
        return $image->getDisk()->exists($this->stylePath($image));
    }

    /**
     * deletes all images associated with this style
     */
    public function deleteImages()
    {
        foreach ($this->images as $image) {
            $this->deleteImage($image);
        }
    }

    /**
     * Deletes an image for a image
     * 
     * @param Image $image
     */
    public function deleteImage(Image $image)
    {
        $path = $this->stylePath($image);
        $image->getDisk()->delete($path);
    }

    /**
     * Has this style image being generated before this style was updated
     * 
     * @param  Image $image
     * @return boolean
     */
    protected function isOutdated(Image $image)
    {
        $thisTime = $this->updated_at->timestamp;
        $mediaTime = $this->getPivotWithMedia($image)->updated_at->timestamp;

        return ($thisTime > $mediaTime);
    }

    /**
     * Url for this style for a image.
     * Will create the image if it doesn't exist
     * 
     * @param  Image $image
     * @return string
     */
    public function url(Image $image)
    {
        if (!$this->existsForMedia($image) or $this->isOutdated($image)) {
            $this->createImage($image);
        }
        return $image->getDisk()->url($this->stylePath($image));
    }

    /**
     * Apply the transformations to a file
     * 
     * @param string $file
     */
    public function applyTransformations(string $file)
    {
        foreach ($this->getTransformations() as $transformation) {
            $transformation->process($file);
        }
    }

    /**
     * Create an image for this style and a image.
     * Returns the relative path of the created image
     * 
     * @param  Image $image
     * @return string|null
     */
    public function createImage(Image $image)
    {
        if (!$image->fileExists()) {
            return;
        }
        //move the image to the temp disk
        $tmpName = $image->copyToTemporaryDisk();
        $tmpFile = temp_path($tmpName);
        //apply transformations
        $this->applyTransformations($tmpFile);
        //move image from temp disk to destination disk
        $target = config('media.folder').'/'.$this->getFolder();
        $image->getDisk()->putFileAs($target, new File($tmpFile), $image->filename);
        //delete image from temp disk
        \Storage::disk('tmp')->delete($tmpName);
        //attach image to image style
        $this->images()->detach($image);
        $this->images()->attach($image);
        $image->load('image_styles');

        return $target.'/'.$image->filename;
    }
}