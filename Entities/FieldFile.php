<?php

namespace Pingu\Media\Entities;

use Pingu\Media\Displayers\DefaultFileDisplayer;
use Pingu\Media\Entities\File;
use Pingu\Media\Forms\Fields\UploadFile;

class FieldFile extends FieldImage
{
    protected static $displayers = [DefaultFileDisplayer::class];

    protected static $availableWidgets = [UploadFile::class];

    /**
     * @inheritDoc
     */
    protected function getModel(): string
    {
        return File::class;
    }
}