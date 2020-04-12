<?php

namespace Pingu\Media;

use Illuminate\Database\Eloquent\Collection;
use Pingu\Media\Entities\ImageStyle as ImageStyleModel;

class ImageStyle
{
    /**
     * Get an image style
     * 
     * @param int|string $style
     * 
     * @return ImageStyleModel
     */
    public function get($style)
    {
        if (is_numeric($style)) {
            return $this->getByInt($style);
        }
        return $this->getByName($style);
    }

    /**
     * Get an image style by id
     * 
     * @param int $style
     * 
     * @return ImageStyleModel
     */
    public function getById(int $style): ?ImageStyleModel
    {
        return $this->resolveCache()->where('id', $style)->first();
    }

    /**
     * Get an image style by name
     * 
     * @param string $style
     * 
     * @return ImageStyleModel
     */
    public function getByName(string $style): ?ImageStyleModel
    {
        return $this->resolveCache()->where('machineName', $style)->first();
    }

    /**
     * Get all image styles cache
     * 
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->resolveCache();
    }

    /**
     * Get all image styles cache
     * 
     * @return Collection
     */
    protected function resolveCache(): Collection
    {
        if (config('media.useCache')) {
            return \Cache::rememberForever(config('media.imageStyleCacheKey'), function () {
                return ImageStyleModel::all();
            });
        }
        return ImageStyleModel::all();
    }

    /**
     * Forget image style cache
     */
    public function forgetCache()
    {
        \Cache::forget(config('media.imageStyleCacheKey'));
    }
}