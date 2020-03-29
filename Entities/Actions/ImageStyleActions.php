<?php

namespace Pingu\Media\Entities\Actions;

use Pingu\Entity\Support\Actions\BaseEntityActions;
use Pingu\Media\Entities\MediaTransformer;

class ImageStyleActions extends BaseEntityActions
{
    public function actions(): array
    {
        return [
            'edit' => [
                'label' => 'Edit',
                'url' => function ($entity) {
                    return $entity->uris()->make('edit', $entity, adminPrefix());
                },
                'access' => function ($entity) {
                    return \Gate::check('edit', $entity);
                }
            ],
            'delete' => [
                'label' => 'Delete',
                'url' => function ($entity) {
                    return $entity->uris()->make('confirmDelete', $entity, adminPrefix());
                },
                'access' => function ($entity) {
                    return \Gate::check('delete', $entity);
                }
            ],
            'transformations' => [
                'label' => 'Transformations',
                'url' => function ($entity) {
                    return $entity::uris()->make('transformations', $entity, adminPrefix());
                },
                'access' => function ($entity) {
                    return \Gate::check('edit', $entity);
                }
            ]
        ];
    }
}