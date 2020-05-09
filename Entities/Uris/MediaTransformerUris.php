<?php

namespace Pingu\Media\Entities\Uris;

use Pingu\Core\Support\Uris\BaseModelUris;
use Pingu\Media\Entities\ImageStyle;

class MediaTransformerUris extends BaseModelUris
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