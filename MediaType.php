<?php

namespace Pingu\Media;

use Illuminate\Database\Eloquent\Collection;
use Pingu\Media\Entities\MediaType as MediaTypeModel;

class MediaType
{
    public function get($mediaType)
    {
        if (is_array($mediaType)) {
            return $this->getByArray($mediaType);
        }
        if (is_numeric($mediaType)) {
            return $this->getByInt($mediaType);
        }
        return $this->getByName($mediaType);
    }

    public function getByArray(array $mediaTypes)
    {
        $_this = $this;
        return array_map(function ($type) use ($_this){
            return $_this->get($type);
        }, $mediaTypes);
    }

    public function getById(int $mediaType): ?MediaTypeModel
    {
        return $this->resolveCache()->where('id', $mediaType)->first();
    }

    public function getByName(string $mediaType): ?MediaTypeModel
    {
        return $this->resolveCache()->where('machineName', $mediaType)->first();
    }

    public function resolveCache(): Collection
    {
        if (config('media.useCache')) {
            return \Cache::rememberForever(config('media.mediaTypeCacheKey'), function () {
                return MediaTypeModel::all();
            });
        }
        return MediaTypeModel::all();
    }

    public function forgetCache()
    {
        \Cache::forget(config('media.mediaTypeCacheKey'));
    }
}