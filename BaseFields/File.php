<?php

namespace Pingu\Media\BaseFields;

use Pingu\Media\Displayers\DefaultFileDisplayer;
use Pingu\Media\Entities\File as FileEntity;

class File extends Image
{
    protected static $displayers = [DefaultFileDisplayer::class];

    /**
     * @inheritDoc
     */
    public function castValue($value)
    {
        if ($value) {
            return FileEntity::find($value);
        }
    }
}