<?php

namespace Pingu\Media\Displayers;

use Pingu\Field\Support\FieldDisplayerWithOptions;
use Pingu\Media\Displayers\Options\DefaultFileOptions;

class DefaultFileDisplayer extends FieldDisplayerWithOptions
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
        return 'file-default';
    }

    /**
     * @ineritDoc
     */
    public static function optionsClass(): string
    {
        return DefaultFileOptions::class;
    }

    /**
     * @inheritDoc
     */
    public function systemView(): string
    {
        return 'media@fields.file-default';
    }

    /**
     * @inheritDoc
     */
    public function getFieldValue($value)
    {
        return $value;
    }
}