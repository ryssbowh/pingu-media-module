<?php

namespace Pingu\Media\Exceptions;

class MediaTransformerException extends \Exception
{

    public static function registered(string $slug, string $class)
    {
        return new static("'{$class::getSlug()}' is already registered by transformer $class");
    }

    public static function notRegistered(string $slug)
    {
        return new static("'$slug' is not a registered transformer slug");
    }

}