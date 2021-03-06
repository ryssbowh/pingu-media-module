<?php

namespace Pingu\Media\Entities;

use Pingu\Core\Traits\Models\HasMachineName;
use Pingu\Entity\Support\Entity;
use Pingu\Media\Entities\Image;
use Pingu\Media\Http\Contexts\EditMediaTypeContext;

class MediaType extends Entity
{
    use HasMachineName;
    
    protected $fillable = ['name', 'machineName', 'extensions', 'icon'];

    protected $visible = ['id', 'name', 'machineName', 'extensions', 'icon'];

    protected $casts = [
        'extensions' => 'json'
    ];

    protected $with = ['icon'];

    public $adminListFields = ['name', 'icon'];

    public static $routeContexts = [EditMediaTypeContext::class];

    public $descriptiveField = 'name';

    public static function boot()
    {
        parent::boot();

        static::deleted(function ($mediaType) {
            \MediaType::forgetCache();
        });

        static::saved(function ($mediaType) {
            \MediaType::forgetCache();
        });
    }

    /**
     * @inheritDoc
     */
    public function getRouteKeyName()
    {
        return 'machineName';
    }

    /**
     * Icon accessor
     * 
     * @return string
     */
    public function friendlyIconAttribute($value)
    {
        if ($value) {
            return $value->img('icon');
        }
        return '';
    }

    public static function getByExtension(string $ext)
    {
        return static::whereJsonContains('extensions', $ext)->first();
    }

    /**
     * Medias relationship
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function medias()
    {
        return $this->hasMany(Media::class);
    }

    /**
     * Count the maount of medias that has this type
     * 
     * @return int
     */
    public function countMedias(): int
    {
        return $this->medias()->count();
    }

    /**
     * Icon relationship
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function icon()
    {
        return $this->belongsTo(Image::class);
    }

    /**
     * Returns the icon url for that type.
     * If the file doesn't exist on disk, return a placeholder
     * 
     * @return 
     */
    public function urlIcon()
    {
        if (!$this->icon or !$this->icon->fileExists()) {
            return module_url('Media', 'not_found.jpg');
        }
        return $this->icon->url();
    }

    /**
     * Transform extensions when passing to a field
     * 
     * @param string $value
     * 
     * @return string
     */
    public function formExtensionsAttribute($value)
    {
        return implode(',', $this->extensions);
    }

    /**
     * Extensions mutator
     * 
     * @param mixed $value
     */
    public function setExtensionsAttribute($value)
    {
        if (is_string($value)) {
            $this->attributes['extensions'] = json_encode(explode(',', $value));
        } else {
            $this->attributes['extensions'] = $value;
        }
    }

}
