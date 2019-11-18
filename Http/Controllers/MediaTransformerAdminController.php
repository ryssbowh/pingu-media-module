<?php

namespace Pingu\Media\Http\Controllers;

use Illuminate\Support\Collection;
use Pingu\Core\Http\Controllers\BaseController;
use Pingu\Entity\Entities\Entity;
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

    // public function index(ImageStyle $style)
    // {
    //     \ContextualLinks::addFromObject($style);
    //     $transformations = $style->getTransformations();
    //     $addForm = new AddTransformerForm($style);
    //     return view('media::imageTransformations')->with([
    //         'style' => $style,
    //         'transformations' => $transformations,
    //         'addForm' => $addForm,
    //         'patchUri' => MediaTransformer::uris()->make('patch', $style, adminPrefix())
    //     ]);
    // }

    // public function onEditOptionsFormCreated(Form $form, MediaTransformer $transformer)
    // {
    //     return view('pages.editModel')->with([
    //         'form' => $form,
    //         'model' => $transformer
    //     ]);
    // }

    // public function onAddOptionsFormCreated(Form $form)
    // {
    //     return view('pages.addModel')->with([
    //         'form' => $form,
    //         'model' => MediaTransformer::class
    //     ]);
    // }

    // protected function onStoreSuccess(BaseModel $model)
    // {
    //     return redirect($model::makeUri('index', $model->image_style, adminPrefix()));
    // }

    // public function onUpdateSuccess(MediaTransformer $transformer)
    // {
    //     \Notify::success($transformer::friendlyName().' has been edited');
    //     return redirect($transformer::makeUri('index', $transformer->image_style, adminPrefix()));
    // }

    // /**
    //  * Action when deletion succeeds
    //  * 
    //  * @param  BaseModel $model
    //  */
    // protected function onDeleteSuccess(BaseModel $model)
    // {
    //     return redirect($model::makeUri('index', $model->image_style, adminPrefix()));
    // }

    // public function onPatchSuccess(Entity $style, Collection $entities)
    // {
    //     return redirect(MediaTransformer::makeUri('index', $style, adminPrefix()));
    // }
}
