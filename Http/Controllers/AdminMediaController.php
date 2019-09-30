<?php

namespace Pingu\Media\Http\Controllers;

use Pingu\Core\Entities\BaseModel;
use Pingu\Core\Http\Controllers\AdminModelController;
use Pingu\Forms\Support\Form;
use Pingu\Media\Entities\Media;

class AdminMediaController extends AdminModelController
{
    public function getModel()
    {
        return Media::class;
    }

    public function store()
    {
        $media = new Media;
        
        $validated = $media->validateStoreRequest($this->request, ['file']);
        \Media::uploadFile($validated['file']);

        \Notify::success("Media has been saved");

        return redirect()->route('media.admin.media');
    }

    protected function onUpdateSuccess(BaseModel $model)
    {
        return redirect()->route('media.admin.media');
    }
}
