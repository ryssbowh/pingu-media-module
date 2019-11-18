<?php

namespace Pingu\Media\Entities;

use Pingu\Entity\Entities\Entity;
use Pingu\Media\Entities\Media as MediaModel;
use Pingu\Media\Entities\Policies\MediaPolicy;

class MediaType extends Entity
{
    protected $fillable = ['name', 'machineName', 'extensions', 'icon'];

    protected $visible = ['id', 'name', 'machineName', 'extensions', 'icon'];

    protected $casts = [
        'extensions' => 'json'
    ];

    protected $with = ['icon'];

    public $adminListFields = ['name', 'icon'];

    public function getPolicy(): string
    {
        return MediaPolicy::class;
    }

    public function getIconFriendlyValue()
    {
        if ($this->icon) {
            return $this->icon->img('icon');
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
     * Icon relationship
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function icon()
    {
        return $this->belongsTo(MediaModel::class);
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
    public function formExtensionsAttribute(string $value)
    {
        return implode(',', $this->extensions);
    }

    public function setExtensionsAttribute($value)
    {
        if (is_string($value)) {
            $this->attributes['extensions'] = json_encode(explode(',', $value));
        } else {
            $this->attributes['extensions'] = $value;
        }
    }

}
