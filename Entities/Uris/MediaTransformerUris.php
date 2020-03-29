<?php

namespace Pingu\Media\Entities\Uris;

use Pingu\Entity\Support\Uris\BaseEntityUris;
use Pingu\Media\Entities\ImageStyle;

class MediaTransformerUris extends BaseEntityUris
{
    protected function uris(): array
    {
        return [
            'create' => ImageStyle::routeSlug().'/{'.ImageStyle::routeSlug().'}/transformations/create',
            'patch' => ImageStyle::routeSlug().'/{'.ImageStyle::routeSlug().'}/transformations',
            'store' => ImageStyle::routeSlug().'/{'.ImageStyle::routeSlug().'}/transformations'
        ];
    }
}