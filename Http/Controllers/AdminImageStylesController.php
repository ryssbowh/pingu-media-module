<?php

namespace Pingu\Media\Http\Controllers;

use Pingu\Core\Http\Controllers\AdminModelController;
use Pingu\Media\Entities\ImageStyle;
use Pingu\Media\Forms\AddTransformerForm;

class AdminImageStylesController extends AdminModelController
{
    public function getModel()
    {
        return ImageStyle::class;
    }

    public function index(...$parameters)
    {
    	return view('media::imageStyles')->with([
    		'styles' => ImageStyle::all(),
    		'stylesClass' => ImageStyle::class
    	]);
    }
    
}
