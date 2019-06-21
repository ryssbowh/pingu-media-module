<?php

namespace Pingu\Media\Http\Controllers;

use Pingu\Core\Entities\BaseModel;
use Pingu\Core\Http\Controllers\AjaxModelController;
use Pingu\Media\Entities\MediaType;

class AjaxMediaTypeController extends AjaxModelController
{
	public function getModel():string
	{
		return MediaType::class;
	}

	protected function performUpdate(BaseModel $model, array $validated)
	{
		$validated['extensions'] = explode(',', $validated['extensions']);
		$model->saveWithRelations($validated);
	}
}
