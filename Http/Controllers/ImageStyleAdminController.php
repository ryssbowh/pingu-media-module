<?php

namespace Pingu\Media\Http\Controllers;

use Pingu\Entity\Entities\Entity;
use Pingu\Entity\Http\Controllers\AdminEntityController;
use Pingu\Forms\Support\Form;
use Pingu\Media\Entities\ImageStyle;
use Pingu\Media\Entities\MediaTransformer;
use Pingu\Media\Forms\AddTransformerForm;

class ImageStyleAdminController extends AdminEntityController
{
    public function transformations(ImageStyle $style)
    {
        \ContextualLinks::addFromObject($style);
        $transformations = $style->getTransformations();
        $addForm = new AddTransformerForm($style);
        return view('media.indexTransformations')->with(
            [
            'style' => $style,
            'transformations' => $transformations,
            'addForm' => $addForm,
            'patchUri' => MediaTransformer::uris()->make('patch', $style, adminPrefix())
            ]
        );
    }

    protected function afterEditFormCreated(Form $form, Entity $entity)
    {
        $form->getElement('machineName')->option('disabled', true);
    }
}
