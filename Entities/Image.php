<?php

namespace Pingu\Media\Entities;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\UploadedFile;
use Pingu\Entity\Support\BundledEntity;
use Pingu\Media\Contracts\MediaContract;
use Pingu\Media\Entities\ImageStyle;
use Pingu\Media\Traits\IsMedia;

class Image extends BundledEntity implements MediaContract
{
    use IsMedia;

    public $timestamps = false;

    public $fillable = ['alt'];

    protected $with = ['image_styles'];

    protected $touches = ['media'];

    public static function createFromUploadedFile(UploadedFile $file): MediaContract
    {
        return static::create([
            'alt' => $file->getClientOriginalName()
        ]);
    }
    
    /**
     * @inheritDoc
     */
    public function bundleName(): string
    {
        return 'image';
    }

    /**
     * Image accessor
     * 
     * @return string
     */
    public function friendlyImageValue(): string
    {
        return '<img src="'.$this->urlIcon().'" alt="'.$this->name.'">';
    }

    /**
     * Image styles relationship
     * 
     * @return BelongsToMany
     */
    public function image_styles(): BelongsToMany
    {
        return $this->belongsToMany(ImageStyle::class, 'image_image_style')->withTimestamps();
    }

    /**
     * Returns the url for a style for this image
     * will look in the styles fallbacks array if this media doesn't have that style
     * 
     * @param string $style
     * @param array  $fallbacks
     * 
     * @return string
     */
    public function urlStyle(string $style, array $fallbacks = []): string
    {
        array_unshift($fallbacks, $style);
        foreach ($fallbacks as $styleName) {
            if ($style = \ImageStyle::getByName($styleName)) {
                return $style->url($this);
            }
        }
        return $this->url();
    }

    /**
     * @param ImageStyle|string $style
     * 
     * @return bool
     */
    public function hasStyle($style): bool
    {
        if (is_string($style)) {
            $style = \ImageStyle::getByName($style);
        }
        return ($this->image_styles()->where(['id' => $style->id])->first() !== null);
    }

    /**
     * Gets this image img tag
     * 
     * @param string|null $style
     * @param array       $fallbacks
     * 
     * @return string
     */
    public function img(?string $style = null, array $fallbacks = [])
    {
        $url = is_null($style) ? $this->url() : $this->urlStyle($style, $fallbacks);
        return '<img src="'.$url.'" alt="'.$this->alt.'">';
    }

    /**
     * Returns an icon url for this image
     * 
     * @return string
     */
    public function urlIcon(): string
    {
        return $this->urlStyle('icon');
    }

    /**
     * Creates all the available styles for this image 
     */
    public function createStyles()
    {
        $media = $this;
        ImageStyle::all()->each(
            function ($style) use ($media) {
                $style->createImage($media);
            }
        );
    }

    /**
     * @inheritDoc
     */
    public function deleteFile()
    {
        $this->deleteStyles();
        $this->getMedia()->deleteFile();
    }

    /**
     * Deletes the styles files for this image
     */
    protected function deleteStyles()
    {
        $this->image_styles->each(
            function ($style) {
                $style->deleteImage($this);
            }
        );
    }
}
