<?php

namespace Pingu\Media\Http\Controllers;

use Pingu\Core\Http\Controllers\AjaxModelController;
use Pingu\Media\Entities\ImageStyle;
use Pingu\Media\Forms\AddTransformerForm;

class AjaxImageStylesController extends AjaxModelController
{
    public function getModel()
    {
        return ImageStyle::class;
    }
    
}
