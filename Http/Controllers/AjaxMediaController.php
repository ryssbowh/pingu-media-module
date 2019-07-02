<?php

namespace Pingu\Media\Http\Controllers;

use Pingu\Core\Http\Controllers\AjaxModelController;
use Pingu\Media\Entities\Media;

class AjaxMediaController extends AjaxModelController
{
	public function getModel():string
	{
		return Media::class;
	}
}
