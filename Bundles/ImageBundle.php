<?php

namespace Pingu\Media\Bundles;

use Illuminate\Database\Eloquent\Collection;
use Pingu\Entity\Support\Bundle\ClassBundle;
use Pingu\Media\Entities\Image;
use Pingu\Media\Entities\Media;

class ImageBundle extends ClassBundle
{   
    /**
     * @inheritDoc
     */
    public function friendlyName(): string
    {
        return 'Image';
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'image';
    }

    /**
     * @inheritDoc
     */
    public function entityFor(): string
    {
        return Image::class;
    }

    /**
     * @inheritDoc
     */
    public function entities(): Collection
    {
        return Media::whereHasMorph('instance', [Image::class]);
    }
}