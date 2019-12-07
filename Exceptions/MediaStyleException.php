<?php

namespace Pingu\Media\Exceptions;

class MediaStyleException extends \Exception
{

    public static function notDefined(string $name)
    {
        return new static("Style $name doesn't exist");
    }

    public static function noLibraryInstalled()
    {
        return new static("You must install GD or Imagick in order to transform images.");
    }

}