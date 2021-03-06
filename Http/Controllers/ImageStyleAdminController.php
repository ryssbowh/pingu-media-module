<?php

namespace Pingu\Media\Http\Controllers;

use Pingu\Entity\Support\Entity;
use Pingu\Entity\Http\Controllers\AdminEntityController;
use Pingu\Forms\Support\Form;
use Pingu\Media\Entities\ImageStyle;
use Pingu\Media\Entities\MediaTransformer;
use Pingu\Media\Forms\AddTransformerForm;

class ImageStyleAdminController extends AdminEntityController
{
    public function transformations(ImageStyle $style)
    {
        \ContextualLinks::addObjectActions($style);
        $transformations = $style->getTransformations();
        $addForm = new AddTransformerForm($style);
        return view('pages.media.indexTransformations')->with(
            [
            'style' => $style,
            'transformations' => $transformations,
            'addForm' => $addForm,
            'patchUri' => MediaTransformer::uris()->make('patch', $style, adminPrefix())
            ]
        );
    }
}
