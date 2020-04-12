<?php

namespace Pingu\Media\Entities;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Pingu\Entity\Support\Entity;
use Pingu\Forms\Contracts\FormRepositoryContract;
use Pingu\Media\Entities\Forms\MediaForms;
use Pingu\Media\Entities\MediaType;
use Pingu\Media\Entities\Policies\MediaPolicy;
use Pingu\Media\Support\MediaCollection;

class Media extends Entity
{
    protected $fillable = ['name', 'disk', 'filename', 'size'];

    protected $visible = ['id', 'name', 'filename', 'disk', 'size', 'media_type'];

    public $adminListFields = ['name', 'filename', 'disk', 'size', 'media_type', 'image'];

    protected $notFilterable = ['disk', 'size', 'filename'];

    protected $with = ['instance'];
    
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
    public function forms(): FormRepositoryContract
    {
        return new MediaForms($this);
    }
    
    /**
     * @inheritDoc
     */
    public function newCollection(array $models = [])
    {
        return new MediaCollection($models);
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
    public function friendlySizeValue($size): string
    {
        return friendly_size($size);
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
            if (sizeof($elems) > 1 and is_numeric($elems[sizeof($elems)-1])) {
                $number = $elems[sizeof($elems)-1] + 1;
                unset($elems[sizeof($elems)-1]);
                $elems[] = $number;
                $name = implode('-', $elems);
            } else {
                $name .= '-1';
            }
            return static::generateUniqueFileName($name.'.'.$ext);
        }
        return $name;
    }

    /**
     * @inheritDoc
     */
    public function media_type(): BelongsTo
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
    public function getMediaTypeAttribute($value): MediaType
    {
        return \MediaType::getById($this->attributes['media_type_id']);
    }

    /**
     * Morph into instance
     * 
     * @return MorphTo
     */
    public function instance(): MorphTo
    {
        return $this->morphTo();
    }
}
