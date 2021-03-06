<?php

namespace Pingu\Media\Entities\Routes;

use Pingu\Entity\Support\Routes\BaseEntityRoutes;

class ImageStyleRoutes extends BaseEntityRoutes
{
    protected function routes(): array
    {
        return [
            'admin' => ['transformations']
        ];
    }

    protected function methods(): array 
    {
        return [
            'transformations' => 'get'
        ];
    }

    protected function middleware(): array 
    {
        return [
            'transformations' => 'can:edit,image_style'
        ];
    }
}