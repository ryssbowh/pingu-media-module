<?php

namespace Pingu\Media;

use Illuminate\Database\Eloquent\Collection;
use Pingu\Media\Entities\ImageStyle as ImageStyleModel;

class ImageStyle
{
    public function get($style)
    {
        if (is_numeric($style)) {
            return $this->getByInt($style);
        }
        return $this->getByName($style);
    }

    public function getById(int $style): ?ImageStyleModel
    {
        return $this->resolveCache()->where('id', $style)->first();
    }

    public function getByName(string $style): ?ImageStyleModel
    {
        return $this->resolveCache()->where('machineName', $style)->first();
    }

    public function resolveCache(): Collection
    {
        if (config('media.useCache')) {
            return \Cache::rememberForever(config('media.imageStyleCacheKey'), function () {
                return ImageStyleModel::all();
            });
        }
        return ImageStyleModel::all();
    }

    public function forgetCache()
    {
        \Cache::forget(config('media.imageStyleCacheKey'));
    }
}