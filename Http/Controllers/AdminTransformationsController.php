<?php

namespace Pingu\Media\Http\Controllers;

use Pingu\Core\Entities\BaseModel;
use Pingu\Core\Traits\Controllers\DeletesAdminModel;
use Pingu\Forms\Support\Form;
use Pingu\Media\Entities\ImageStyle;
use Pingu\Media\Entities\MediaTransformer;
use Pingu\Media\Forms\AddTransformerForm;
use Pingu\Media\Forms\AddTransformerOptionsForm;
use Pingu\Media\Forms\EditTransformerOptionsForm;
use Pingu\Media\Http\Requests\TransformerOptionsRequest;

class AdminTransformationsController extends TransformationsController
{
    use DeletesAdminModel;

    public function index(ImageStyle $style)
    {
        \ContextualLinks::addModelLinks($style);
        $transformations = $style->getTransformations();
        $addForm = new AddTransformerForm($style);
        return view('media::imageTransformations')->with([
            'style' => $style,
            'transformations' => $transformations,
            'addForm' => $addForm,
            'patchUri' => MediaTransformer::makeUri('patch', $style, adminPrefix())
        ]);
    }

    public function onEditOptionsFormCreated(Form $form, MediaTransformer $transformer)
    {
        return view('pages.editModel')->with([
            'form' => $form,
            'model' => $transformer
        ]);
    }

    public function onAddOptionsFormCreated(Form $form)
    {
        return view('pages.addModel')->with([
            'form' => $form,
            'model' => MediaTransformer::class
        ]);
    }

    protected function onStoreSuccess(BaseModel $model)
    {
        return redirect($model::makeUri('index', $model->image_style, adminPrefix()));
    }

    public function onUpdateSuccess(MediaTransformer $transformer)
    {
        \Notify::success($transformer::friendlyName().' has been edited');
        return redirect($transformer::makeUri('index', $transformer->image_style, adminPrefix()));
    }

    /**
     * Action when deletion succeeds
     * 
     * @param  BaseModel $model
     */
    protected function onDeleteSuccess(BaseModel $model)
    {
        return redirect($model::makeUri('index', $model->image_style, adminPrefix()));
    }

    public function onPatchSuccess(ImageStyle $style)
    {
        return redirect(MediaTransformer::makeUri('index', $style, adminPrefix()));
    }
}
