<?php

namespace Pingu\Media\Entities\Actions;

use Pingu\Core\Support\Actions\BaseAction;
use Pingu\Entity\Support\Actions\BaseEntityActions;

class ImageStyleActions extends BaseEntityActions
{
    public function actions(): array
    {
        return [
            'transformations' => new BaseAction(
                'Transformations',
                function ($entity) {
                    return $entity::uris()->make('transformations', $entity, adminPrefix());
                },
                function ($entity) {
                    return \Gate::check('edit', $entity);
                },
                'admin'
            )
        ];
    }
}