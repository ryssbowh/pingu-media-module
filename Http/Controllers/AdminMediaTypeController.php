<?php

namespace Pingu\Media\Http\Controllers;

use Pingu\Core\Entities\BaseModel;
use Pingu\Core\Http\Controllers\AdminModelController;
use Pingu\Media\Entities\MediaType;

class AdminMediaTypeController extends AdminModelController
{
    public function getModel()
    {
        return MediaType::class;
    }

    /**
	 * @inheritDoc
	 */
	protected function performStore(BaseModel $model, array $validated)
	{
		$validated['extensions'] = explode(',', $validated['extensions']);
		$model->saveWithRelations($validated);
	}
}
