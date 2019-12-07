<?php

namespace Pingu\Media\Exceptions;

class MediaException extends \Exception
{
    public static function nameExists(string $name)
    {
        return new static("A media called '$name' already exist");
    }

}