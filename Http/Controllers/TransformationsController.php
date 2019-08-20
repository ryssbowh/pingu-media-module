<?php

namespace Pingu\Media\Http\Controllers;

use Pingu\Core\Entities\BaseModel;
use Pingu\Core\Http\Controllers\BaseController;
use Pingu\Core\Traits\Controllers\PatchesAjaxModel;
use Pingu\Media\Entities\ImageStyle;
use Pingu\Media\Entities\MediaTransformer;
use Pingu\Media\Forms\AddTransformerOptionsForm;
use Pingu\Media\Forms\EditTransformerOptionsForm;
use Pingu\Media\Http\Requests\AddTransformerOptionsRequest;
use Pingu\Media\Http\Requests\EditTransformerOptionsRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TransformationsController extends BaseController
{
    public function store(ImageStyle $style, AddTransformerOptionsRequest $request)
    {
    	$slug = $this->request->post('_transformer');
        $model = $this->performStore($style, $request->validated(), \Media::getTransformer($slug));
        return $this->onStoreSuccess($model);
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

    public function update(MediaTransformer $transformer, EditTransformerOptionsRequest $request)
    {
        $transformer->options = $request->validated();
        $transformer->save();
        return $this->onUpdateSuccess($transformer);
    }

    public function patch(ImageStyle $style)
    {
        if(!$weights = $this->request->post('weights')){
            throw new HttpException("Weights are not set", 422);
        }
        foreach($weights as $id => $weight){
            $transformer = MediaTransformer::findOrFail($id);
            $transformer->weight = $weight;
            $transformer->save();
        }
        return $this->onPatchSuccess($style);
    }

    public function create(ImageStyle $style)
    {
        $slug = $this->request->input('transformer');
        $transformer = \Media::getTransformer($slug);
        if($transformer::hasOptions()){
            $form = new AddTransformerOptionsForm(new $transformer, $style);
            return $this->onAddOptionsFormCreated($form);
        }
        $model = $this->performStore($style, [], $transformer);
        return $this->onStoreSuccess($model);
    }

    public function edit(MediaTransformer $transformer)
    {
        $instance = $transformer->instance();
        if($instance::hasOptions()){
            $form = new EditTransformerOptionsForm($instance);
            return $this->onEditOptionsFormCreated($form, $transformer);
        }
        throw new HttpException(500, "Congratulations, you've ended up nowhere !");
    }

}
