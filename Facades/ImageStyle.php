<?php

namespace Pingu\Media\Facades;

use Illuminate\Support\Facades\Facade;

class ImageStyle extends Facade
{
    protected static function getFacadeAccessor()
    {

        return 'media.imageStyle';

    }
}