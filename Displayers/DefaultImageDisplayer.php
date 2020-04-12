<?php

namespace Pingu\Media\Displayers;

use Pingu\Field\Support\FieldDisplayerWithOptions;
use Pingu\Media\Displayers\Options\DefaultImageOptions;
use Pingu\Media\Entities\Image;

class DefaultImageDisplayer extends FieldDisplayerWithOptions
{
    /**
     * @ineritDoc
     */
    public static function friendlyName(): string
    {
        return 'Default';
    }

    /**
     * @ineritDoc
     */
    public static function machineName(): string
    {
        return 'image-default';
    }

    /**
     * @ineritDoc
     */
    public static function optionsClass(): string
    {
        return DefaultImageOptions::class;
    }

    /**
     * @inheritDoc
     */
    public function systemView(): string
    {
        return 'media@fields.image-default';
    }

    /**
     * @inheritDoc
     */
    public function getFieldValue($value)
    {
        return $value;
    }
}