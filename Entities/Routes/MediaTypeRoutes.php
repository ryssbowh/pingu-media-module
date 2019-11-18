<?php

namespace Pingu\Media\Entities\Routes;

use Pingu\Entity\Support\BaseEntityRoutes;

class MediaTypeRoutes extends BaseEntityRoutes
{
    public function names(): array
    {
        return [
            'admin.index' => 'media.admin.mediaTypes'
        ];
    }
}