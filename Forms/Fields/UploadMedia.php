<?php

namespace Pingu\Media\Forms\Fields;

use Pingu\Forms\Support\Field;
use Pingu\Media\Entities\Media as MediaModel;

class UploadMedia extends Field
{
    /**
     * @inheritDoc
     */
    protected $attributeOptions = ['required', 'id', 'accept'];

    public function getMedia()
    {
        return MediaModel::find($this->value);
    }

    /**
     * @inheritDoc
     */
    public function systemView(): string
    {
        return 'media@fields.uploadmedia';
    }
}