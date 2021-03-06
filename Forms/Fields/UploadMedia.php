<?php

namespace Pingu\Media\Forms\Fields;

use Pingu\Forms\Support\Field;

class UploadMedia extends Field
{
    /**
     * @inheritDoc
     */
    public function getAttributeOptions(): array
    {
        return array_merge(parent::getAttributeOptions(), ['accept']);
    }

    /**
     * @inheritDoc
     */
    public function systemView(): string
    {
        return 'media@forms.fields.uploadmedia';
    }
}