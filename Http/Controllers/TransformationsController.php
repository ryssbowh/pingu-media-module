<?php

namespace Pingu\Media\Http\Controllers;

use Pingu\Entity\Support\Entity;
use Pingu\Entity\Http\Controllers\AdminEntityController;
use Pingu\Entity\Traits\Controllers\Entities\CreatesEntity;
use Pingu\Entity\Traits\Controllers\Entities\DeletesEntity;
use Pingu\Entity\Traits\Controllers\Entities\EditsEntity;
use Pingu\Entity\Traits\Controllers\Entities\PatchesEntity;
use Pingu\Media\Entities\ImageStyle;
use Pingu\Media\Entities\MediaTransformer;
use Pingu\Media\Http\Requests\AddTransformerOptionsRequest;
use Pingu\Media\Http\Requests\EditTransformerOptionsRequest;

trait TransformationsController
{
    use CreatesEntity {
        create as traitCreate;
    }
    use EditsEntity, DeletesEntity, PatchesEntity;

    public function create()
    {
        $style = $this->routeParameter(ImageStyle::routeSlug());
        $slug = $this->request->input('transformer');
        $transformer = \Media::getTransformer($slug);
        if (!$transformer::hasOptions()) {
            $model = $this->performStore($style, [], $transformer);
            return $this->onStoreSuccess($model);
        }
        return $this->traitCreate();
    }

    protected function getCreateForm(Entity $entity)
    {
        $url = $this->getStoreUri($entity);

        $slug = $this->request->input('transformer');
        $transformer = \Media::getTransformer($slug);
        $form = $entity->forms()->create([$url, new $transformer]);

        return $form;
    }

    protected function performStore(ImageStyle $style, array $options, string $transformerClass)
    {
        $transformer = new MediaTransformer;
        $transformer->options = $options;
        $transformer->class = $transformerClass;
        $transformer->weight = MediaTransformer::getNextWeight(['image_style_id' => $style->id]);
        $transformer->image_style()->associate($style);
        $transformer->save();
        return $transformer;
    }

    public function store(AddTransformerOptionsRequest $request, ImageStyle $style)
    {
        $slug = $this->request->post('_transformer');
        $model = $this->performStore($style, $request->validated(), \Media::getTransformer($slug));
        return $this->onStoreSuccess($model);
    }

    public function update(EditTransformerOptionsRequest $request, MediaTransformer $transformer)
    {
        $transformer->options = $request->validated();
        $transformer->save();
        return $this->onUpdateSuccess($transformer);
    }

    // protected function performStore(ImageStyle $style, array $options, string $transformerClass)
    // {
    //     $transformer = new MediaTransformer;
    //     $transformer->options = $options;
    //     $transformer->class = $transformerClass;
    //     $transformer->weight = MediaTransformer::getNextWeight(['image_style_id' => $style->id]);
    //     $transformer->image_style()->associate($style);
    //     $transformer->save();
    //     return $transformer;
    // }

    // public function update(MediaTransformer $transformer, EditTransformerOptionsRequest $request)
    // {
    //     $transformer->options = $request->validated();
    //     $transformer->save();
    //     return $this->onUpdateSuccess($transformer);
    // }

    // public function create(ImageStyle $style)
    // {
    //     $slug = $this->request->input('transformer');
    //     $transformer = \Media::getTransformer($slug);
    //     if($transformer::hasOptions()){
    //         $form = new AddTransformerOptionsForm(new $transformer, $style);
    //         return $this->onAddOptionsFormCreated($form);
    //     }
    //     $model = $this->performStore($style, [], $transformer);
    //     return $this->onStoreSuccess($model);
    // }

    // public function edit(MediaTransformer $transformer)
    // {
    //     $instance = $transformer->instance();
    //     if($instance::hasOptions()){
    //         $form = new EditTransformerOptionsForm($instance);
    //         return $this->onEditOptionsFormCreated($form, $transformer);
    //     }
    //     throw new HttpException(500, "Congratulations, you've ended up nowhere !");
    // }

}
