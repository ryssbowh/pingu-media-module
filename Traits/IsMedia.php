<?php 

namespace Pingu\Media\Traits;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Filesystem\FilesystemContract;
use Pingu\Core\Support\Actions;
use Pingu\Core\Support\Routes;
use Pingu\Core\Support\Uris;
use Pingu\Media\Entities\Media;
use Pingu\Media\Entities\MediaType;
use Pingu\Media\Entities\Policies\MediaPolicy;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait IsMedia
{
    /**
     * Attributes shared with media
     * @var array
     */
    protected $mediaAttributes = ['name', 'filename', 'size', 'folder', 'media_type', 'disk'];

    public static function bootIsMedia()
    {
        static::saving(function ($media) {
            if ($media->media) {
                $media->media->save();
            }
        });
    }

    /**
     * @inheritDoc
     */
    public static function actions(): Actions
    {
        return \Actions::get(Media::class);
    }

    /**
     * @inheritDoc
     */
    public static function uris(): Uris
    {
        return \Uris::get(Media::class);
    }

    /**
     * @inheritDoc
     */
    public static function routes(): Routes
    {
        return \Routes::get(Media::class);
    }

    /**
     * @inheritDoc
     */
    public static function routeSlug(): string
    {
        return class_machine_name(Media::class);
    }

    /**
     * @inheritDoc
     */
    public static function routeSlugs(): string
    {
        return 'medias';
    }

    /**
     * Morph relationship
     * 
     * @return MorphMany
     */
    public function media(): MorphOne
    {
        return $this->morphOne(Media::class, 'instance');
    }

    /**
     * @inheritDoc
     */
    public function getPolicy(): string
    {
        return MediaPolicy::class;
    }

    /**
     * @inheritDoc
     */
    public function getAttribute($key)
    {
        if (in_array($key, $this->mediaAttributes)) {
            return $this->media->getAttribute($key);
        }
        return parent::getAttribute($key);
    }

    /**
     * @inheritDoc
     */
    public function isDirty($attributes = null)
    {
        return (parent::isDirty() or $this->media->isDirty());
    }

    /**
     * @inheritDoc
     */
    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->mediaAttributes)) {
            return $this->media->setAttribute($key, $value);
        }
        return parent::setAttribute($key, $value);
    }

    /**
     * @inheritDoc
     */
    public function getFillable()
    {
        return array_merge(parent::getFillable(), $this->mediaAttributes);
    }

    /**
     * @inheritDoc
     */
    public function wasChanged($attributes = null)
    {
        $attributes = is_array($attributes) ? $attributes : func_get_args();
        return (parent::wasChanged($attributes) or $this->media->wasChanged($attributes));
    }

    /**
     * @inheritDoc
     */
    public function getRouteKey()
    {
        return $this->media->id;
    }

    /**
     * Includes the url of the media and the icon to json requests
     * 
     * @return array
     */
    public function toArray(): array
    {
        return array_merge(parent::toArray(), $this->media->toArray());
    }

    /**
     * @inheritDoc
    */
    public function url(): string
    {
        if (!$this->fileExists()) {
            return $this->media_type->urlIcon();
        }
        return $this->getDisk()->url($this->getPath());
    }

    /**
     * @inheritDoc
     */
    public function lastModified(?string $format = null)
    {
        $date = $this->getDisk()->lastModified($this->getPath());
        return is_null($format) ? $date : date($format, $date);
    }

    /**
     * @inheritDoc
     */
    public function size(?string $unit = null): int
    {
        if ($unit) {
            return friendly_size($this->size, $unit);
        }
        return $this->size;
    }

    /**
     * @inheritDoc
     */
    public function deleteFile()
    {
        $this->getDisk()->delete($this->getPath());
    }

    /**
     * @inheritDoc
     */
    public function getExtension(): string
    {
        $elems = explode('.', $this->filename);
        return end($elems);
    }

        /**
     * @inheritDoc
     */
    public function getPath(): string
    {
        return config('media.folder').'/'.$this->filename;
    }

    /**
     * @inheritDoc
     */
    public function getDisk(): Filesystem
    {
        return \Storage::disk($this->disk);
    }

    /**
     * @inheritDoc
     */
    public function getContent(): string
    {
        return $this->getDisk()->get($this->getPath());
    }

    /**
     * @inheritDoc
     */
    public function fileExists(): bool
    {
        return $this->getDisk()->exists($this->getPath());
    }

    /**
     * @inheritDoc
     */
    public function download(?string $name = null, array $headers = []): StreamedResponse
    {
        return $this->getDisk()->download($this->getPath(), $name, $headers);
    }

    /**
     * Copy the content of this media to the temporary disk
     * 
     * @return string name of the file in temporary disk
     */
    public function copyToTemporaryDisk(): string
    {
        $tmpName = uniqid('image-', true).'.'.$this->getExtension();
        \Storage::disk('tmp')->put($tmpName, $this->getContent());
        return $tmpName;
    }

    /**
     * @inheritDoc
     */
    public function register()
    {
        $this->fireModelEvent('registering');
        \Entity::registerEntity($this);
        \Policies::register($this, $this->getPolicy());
        $this->fireModelEvent('registered');
    }
}