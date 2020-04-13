<?php

namespace Pingu\Media\Bundles;

use Illuminate\Support\Collection;
use Pingu\Entity\Support\Bundle\ClassBundle;
use Pingu\Media\Entities\File;
use Pingu\Media\Entities\Media;

class FileBundle extends ClassBundle
{   
    /**
     * @inheritDoc
     */
    public function friendlyName(): string
    {
        return 'File';
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'file';
    }

    /**
     * @inheritDoc
     */
    public function entityFor(): string
    {
        return File::class;
    }

    /**
     * @inheritDoc
     */
    public function entities(): Collection
    {
        return Media::whereHasMorph('instance', [File::class]);
    }
}