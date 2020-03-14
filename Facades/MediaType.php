<?php

namespace Pingu\Media\Facades;

use Illuminate\Support\Facades\Facade;

class MediaType extends Facade
{
    protected static function getFacadeAccessor()
    {

        return 'media.mediaType';

    }
}