<?php

namespace Pingu\Media\Entities;

use Pingu\Entity\Support\BundledEntity;
use Pingu\Forms\Contracts\FormRepositoryContract;
use Pingu\Media\Entities\Forms\MediaForms;
use Pingu\Media\Entities\ImageStyle;
use Pingu\Media\Entities\MediaType;
use Pingu\Media\Entities\Policies\MediaPolicy;

class Media extends BundledEntity
{
    protected $fillable = ['name', 'disk', 'filename', 'size'];

    protected $filterable = ['name', 'media_type'];

    protected $visible = ['id', 'name', 'filename', 'disk', 'size', 'media_type'];

    protected $with = ['image_styles'];

    public $adminListFields = ['name', 'filename', 'disk', 'size', 'media_type', 'image'];

    protected $notFilterable = ['disk', 'size', 'filename'];

    /**
     * @inheritDoc
     */
    public static function routeSlugs(): string
    {
        return 'medias';
    }

    /**
     * @inheritDoc
     */
    public function bundleName(): string
    {
        return 'media';
    }
    
    public function forms(): FormRepositoryContract
    {
        return new MediaForms($this);
    }

    /**
     * @inheritDoc
     */
    public function getPolicy(): string
    {
        return MediaPolicy::class;
    }

    /**
     * Media type accessor
     * 
     * @return string
     */
    public function friendlyMediaTypeValue($mediaType)
    {
        return $mediaType->name;
    }

    /**
     * Size accessor
     * 
     * @return string
     */
    public function friendlySizeValue($size)
    {
        return friendly_size($size);
    }

    /**
     * Image accessor
     * 
     * @return string
     */
    public function friendlyImageValue()
    {
        return '<img src="'.$this->urlIcon().'" alt="'.$this->name.'">';
    }

    /**
     * Includes the url of the media and the icon to json requests
     * 
     * @return array
     */
    public function toArray()
    {
        $array = parent::toArray();
        if ($this->exists) {
            $array['image']['media'] = $this->url();
            $array['image']['icon'] = $this->urlIcon();
        }
        return $array;
    }

    /**
     * Generate a unique name for a file
     * 
     * @param string $name
     * 
     * @return string
     */
    public static function generateUniqueFileName(string $name): string
    {
        $media = static::where('filename', '=', $name)->first();
        if ($media) {
            $elems = explode('.', $name);
            $ext = $elems[sizeof($elems)-1];
            unset($elems[sizeof($elems)-1]);
            $name = implode('.', $elems);
            $elems = explode('-', $name);
            if(sizeof($elems) > 1 and is_numeric($elems[sizeof($elems)-1])) {
                $number = $elems[sizeof($elems)-1] + 1;
                unset($elems[sizeof($elems)-1]);
                $elems[] = $number;
                $name = implode('-', $elems);
            }
            else{
                $name .= '-1';
            }
            return static::generateUniqueFileName($name.'.'.$ext);
        }
        return $name;
    }

    /**
     * Media type relation
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongTo
     */
    public function media_type()
    {
        return $this->belongsTo(MediaType::class);
    }

    /**
     * Media type accessor
     * 
     * @param mixed $value
     * 
     * @return MediaType
     */
    public function getMediaTypeAttribute($value)
    {
        return \MediaType::getById($this->attributes['media_type_id']);
    }

    /**
     * Image styles relationship
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function image_styles()
    {
        return $this->belongsToMany(ImageStyle::class)->withTimestamps();
    }

    /**
     * Get this media relative path
     * 
     * @return string
     */
    public function getPath()
    {
        return config('media.folder').'/'.$this->filename;
    }

    /**
     * Get the disk instance this media is stored in
     * 
     * @return Filesystem
     */
    public function getDisk()
    {
        return \Storage::disk($this->disk);
    }

    /**
     * Get the raw content of this media
     * 
     * @return string
     */
    public function getContent()
    {
        return $this->getDisk()->get($this->getPath());
    }

    /**
     * Does the file exist on disk
     * 
     * @return bool
     */
    public function fileExists()
    {
        return $this->getDisk()->exists($this->getPath());
    }

    /**
     * @param  ImageStyle $style
     * @return bool
     */
    public function styleExists(ImageStyle $style)
    {
        return ($this->image_styles()->where(['id' => $style->id])->first() !== null);
    }

    /**
     * Force download of this media
     * 
     * @param string|null $name
     * @param array       $headers
     * 
     * @return StreamedResponse
     */
    public function download(?string $name = null, array $headers = [])
    {
        return $this->getDisk()->download($this->getpath(), $name, $headers);
    }

    /**
     * Is this media an image
     * 
     * @return boolean
     */
    public function isImage()
    {
        return $this->media_type->machineName == 'image';
    }

    /**
     * Returns the url for a style for this media
     * will look in the styles fallbacks array if this media doesn't have that style
     * 
     * @param  string $style
     * @param  array  $fallbacks
     * @return string
     */
    protected function urlStyle(string $style, array $fallbacks = [])
    {
        array_unshift($fallbacks, $style);
        foreach ($fallbacks as $styleName) {
            if ($style = \ImageStyle::getByName($styleName)) {
                return $style->url($this);
            }
        }
        return $this->getDisk()->url($this->getPath());
    }
    
    /**
     * Gets this media url, for images a style can be given
     * 
     * @param string|null $style
     * @param array       $fallbacks
     * 
     * @return string
     */
    public function url(?string $style = null, array $fallbacks = [])
    {
        if (!$this->fileExists()) {
            return $this->media_type->urlIcon();
        }
        if (!is_null($style) and $this->isImage()) {
            return $this->urlStyle($style, $fallbacks);
        }
        return $this->getDisk()->url($this->getPath());
    }

    /**
     * Gets this media img tag
     * 
     * @param string|null $style
     * @param array       $fallbacks
     * 
     * @return string
     */
    public function img(?string $style = null, array $fallbacks = [])
    {
        $url = $this->url($style, $fallbacks);
        return '<img src="'.$url.'" alt="'.$this->name.'">';
    }

    /**
     * Returns an icon url for this media
     * 
     * @return string
     */
    public function urlIcon()
    {
        if ($this->isImage()) {
            return $this->url('icon');
        }
        return $this->media_type->urlIcon();
    }

    /**
     * When was the file updated
     * 
     * @return int
     */
    public function lastModified()
    {
        return $this->getDisk()->lastModified($this->getPath());
    }

    /**
     * Gets this media's size
     * 
     * @return integer
     */
    public function size()
    {
        return $this->size;
    }

    /**
     * Creates all the available styles for that media 
     */
    public function createStyles()
    {
        if($this->isImage()) {
            $media = $this;
            ImageStyle::all()->each(
                function ($style) use ($media) {
                    $style->createImage($media);
                }
            );
        }
    }

    /**
     * Delete this media's file, and all its styles if it's an image
     */
    public function deleteFile()
    {
        $this->deleteStyles();
        $this->getDisk()->delete($this->getPath());
    }

    /**
     * Deletes the styles files for this media (images only)
     */
    protected function deleteStyles()
    {
        if (!$this->isImage()) { return;
        }
        $this->image_styles->each(
            function ($style) {
                $style->deleteImage($this);
            }
        );
    }

    /**
     * Get the extension forthat media
     * 
     * @return string
     */
    public function getExtension()
    {
        $elems = explode('.', $this->filename);
        return end($elems);
    }

    /**
     * Copy the content of this media to the temporary disk
     * 
     * @return string name of the file in temporary disk
     */
    public function copyToTemporaryDisk()
    {
        $tmpName = uniqid('image-', true).'.'.$this->getExtension();
        \Storage::disk('tmp')->put($tmpName, $this->getContent());
        return $tmpName;
    }
}
