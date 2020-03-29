<?php

namespace Pingu\Media\Bundles;

use Illuminate\Database\Eloquent\Collection;
use Pingu\Entity\Support\Bundle\ClassBundle;
use Pingu\Media\Entities\Media;

class MediaBundle extends ClassBundle
{   
    /**
     * @inheritDoc
     */
    public function friendlyName(): string
    {
        return 'Media';
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'media';
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