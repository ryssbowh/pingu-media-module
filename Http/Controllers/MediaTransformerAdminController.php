<?php

namespace Pingu\Media\Http\Controllers;

use Illuminate\Support\Collection;
use Pingu\Core\Http\Controllers\BaseController;
use Pingu\Entity\Support\Entity;
use Pingu\Forms\Support\Form;
use Pingu\Media\Entities\ImageStyle;
use Pingu\Media\Entities\MediaTransformer;

class MediaTransformerAdminController extends BaseController
{
    use TransformationsController;

    protected function getStoreUri(Entity $entity)
    {
        $style = $this->routeParameter(ImageStyle::routeSlug());
        return ['url' => $entity->uris()->make('store', $style, adminPrefix())];
    }

    protected function getUpdateUri(Entity $entity)
    {
        return ['url' => $entity->uris()->make('update', $entity, adminPrefix())];
    }

    protected function getDeleteUri(Entity $entity)
    {
        return ['url' => $entity->uris()->make('delete', $entity, adminPrefix())];
    }

    protected function onCreateFormCreated(Form $form, Entity $entity)
    {
        $with = [
            'form' => $form,
            'entity' => $entity,
        ];
        return view('entity::createEntity')->with($with);
    }

    protected function onEditFormCreated(Form $form, Entity $entity)
    {
        $with = [
            'form' => $form,
            'entity' => $entity,
        ];
        return view('entity::editEntity')->with($with);
    }

    public function confirmDelete(Entity $entity)
    {
        $form = $entity->forms()->delete([$this->getDeleteUri($entity)]);

        $with = [
            'form' => $form, 
            'entity' => $entity
        ];
        return view('entity::deleteEntity')->with($with);
    }

    /**
     * @inheritDoc
     */
    protected function onStoreSuccess(Entity $entity)
    {
        \Notify::success($entity::friendlyName().' has been created');
        return redirect(ImageStyle::uris()->make('transformations', $entity->image_style, adminPrefix()));
    }

    protected function onUpdateSuccess(Entity $entity)
    {
        \Notify::success($entity::friendlyName().' has been updated');
        return redirect(ImageStyle::uris()->make('transformations', $entity->image_style, adminPrefix()));
    }

    protected function onDeleteSuccess(Entity $entity)
    {
        \Notify::success($entity::friendlyName().' has been deleted');
        return redirect(ImageStyle::uris()->make('transformations', $entity->image_style, adminPrefix()));
    }

    protected function onPatchSuccess(Entity $entity, Collection $entities)
    {
        \Notify::success($entity::friendlyNames().' has been saved');
        return redirect(ImageStyle::uris()->make('transformations', $entity->image_style, adminPrefix()));
    }
}
