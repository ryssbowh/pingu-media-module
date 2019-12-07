<?php

namespace Pingu\Media\Exceptions;

class MediaTypeException extends \Exception
{

    public static function extensionNotDefined(string $ext)
    {
        return new static("No media type defined to handle '$ext' files");
    }

}