<?php

namespace Pingu\Media\Http\Controllers;

use Illuminate\Support\Collection;
use Pingu\Core\Http\Controllers\BaseController;
use Pingu\Entity\Support\Entity;
use Pingu\Forms\Support\Form;
use Pingu\Media\Entities\ImageStyle;

class MediaTransformerAjaxController extends BaseController
{
    use TransformationsController;

    protected function onStoreSuccess(Entity $entity)
    {
        return ['entity' => $entity, 'message' => $entity::friendlyName()." has been created"];
    }

    public function onUpdateSuccess(Entity $entity)
    {
        return ['entity' => $entity, 'message' => $entity->instance()::getName().' has been edited'];
    }

    protected function onDeleteSuccess(Entity $entity)
    {
        return ['entity' => $entity, 'message' => $entity->instance()::getName().' has been deleted'];
    }

    public function onPatchSuccess(Entity $entity, Collection $entities)
    {
        return ['entities' => $entities, 'message' => $entity->friendlyNames().' has been saved'];
    }

    protected function getStoreUri(Entity $entity)
    {
        $style = $this->routeParameter(ImageStyle::routeSlug());
        return ['url' => $entity->uris()->make('store', $style, ajaxPrefix())];
    }

    protected function getUpdateUri(Entity $entity)
    {
        return ['url' => $entity->uris()->make('update', $entity, ajaxPrefix())];
    }

    protected function getDeleteUri(Entity $entity)
    {
        return ['url' => $entity->uris()->make('delete', $entity, ajaxPrefix())];
    }

    protected function onCreateFormCreated(Form $form, Entity $entity)
    {
        return ['html' => $form->render()];
    }

    protected function onEditFormCreated(Form $form, Entity $entity)
    {
        return ['html' => $form->render()];
    }
}
