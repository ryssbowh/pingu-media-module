<?php

namespace Pingu\Media\Forms\Fields;

use Pingu\Forms\Support\Field;
use Pingu\Media\Entities\Image;

class UploadImage extends Field
{
    /**
     * @inheritDoc
     */
    public function getAttributeOptions(): array
    {
        return array_merge(parent::getAttributeOptions(), ['accept']);
    }

    public function getImage()
    {
        return Image::find($this->value);
    }

    /**
     * @inheritDoc
     */
    public function systemView(): string
    {
        return 'media@forms.fields.uploadimage';
    }
}