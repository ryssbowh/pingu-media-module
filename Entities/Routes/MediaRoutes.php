<?php

namespace Pingu\Media\Entities\Routes;

use Pingu\Entity\Support\BaseEntityRoutes;

class MediaRoutes extends BaseEntityRoutes
{
    protected function names(): array
    {
        return [
            'admin.index' => 'media.admin.media',
            'admin.create' => 'media.admin.media.create'
        ];
    }
}