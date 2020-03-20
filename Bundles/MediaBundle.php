<?php

namespace Pingu\Media\Bundles;

use Illuminate\Database\Eloquent\Collection;
use Pingu\Entity\Support\Bundle;
use Pingu\Media\Entities\Media;

class MediaBundle extends Bundle
{   
    /**
     * @inheritDoc
     */
    public function bundleFriendlyName(): string
    {
        return 'Media';
    }

    /**
     * @inheritDoc
     */
    public function bundleName(): string
    {
        return 'media.media';
    }

    /**
     * @inheritDoc
     */
    public function getRouteKey(): string
    {
        return 'media.media';
    }

    /**
     * @inheritDoc
     */
    public function entityFor(): string
    {
        return Media::class;
    }

    /**
     * @inheritDoc
     */
    public function entities(): Collection
    {
        return Media::get();
    }
}