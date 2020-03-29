<?php

namespace Pingu\Media\Entities\Uris;

use Pingu\Entity\Support\Uris\BaseEntityUris;
use Pingu\Media\Entities\ImageStyle;

class ImageStyleUris extends BaseEntityUris
{
    protected function uris(): array
    {
        return [
            'transformations' => ImageStyle::routeSlug().'/{'.ImageStyle::routeSlug().'}/transformations'
        ];
    }
}