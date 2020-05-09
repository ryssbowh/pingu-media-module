<?php

namespace Pingu\Media\Entities\Uris;

use Pingu\Core\Support\Uris\BaseModelUris;
use Pingu\Media\Entities\ImageStyle;

class ImageStyleUris extends BaseModelUris
{
    protected function uris(): array
    {
        return [
            'transformations' => ImageStyle::routeSlug().'/{'.ImageStyle::routeSlug().'}/transformations'
        ];
    }
}