<?php

namespace Pingu\Media\Http\Controllers;

use Pingu\Core\Entities\BaseModel;
use Pingu\Core\Traits\Controllers\DeletesAdminModel;
use Pingu\Core\Traits\Controllers\DeletesAjaxModel;
use Pingu\Forms\Support\Form;
use Pingu\Media\Entities\ImageStyle;
use Pingu\Media\Entities\MediaTransformer;
use Pingu\Media\Forms\AddTransformerForm;
use Pingu\Media\Forms\AddTransformerOptionsForm;
use Pingu\Media\Forms\EditTransformerOptionsForm;
use Pingu\Media\Http\Requests\TransformerOptionsRequest;

class AjaxTransformationsController extends TransformationsController
{
    use DeletesAjaxModel;

    public function onEditOptionsFormCreated(Form $form, MediaTransformer $transformer)
    {
        return ['form' => $form->isAjax()
            ->removeField('_back')
            ->addViewSuggestion('forms.modal')
            ->renderAsString()
        ];
    }

    public function onAddOptionsFormCreated(Form $form)
    {
        return ['form' => $form->isAjax()
            ->addViewSuggestion('forms.modal')
            ->removeField('_back')
            ->renderAsString()
        ];
    }

    protected function onStoreSuccess(BaseModel $model)
    {
        return ['model' => $model, 'message' => $model::friendlyName()." has been created"];
    }

    public function onUpdateSuccess(MediaTransformer $transformer)
    {
        return ['message' => $transformer->instance()::getName().' has been edited'];
    }

    /**
     * @inheritDoc
     */
    protected function onDeleteSuccess(BaseModel $model)
    {
        return ['message' => $model->instance()::getName().' has been deleted'];
    }

    public function onPatchSuccess(ImageStyle $style)
    {
        return ['message' => $style->name.' has been saved'];
    }
}
