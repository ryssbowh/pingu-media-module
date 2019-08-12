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
        $model = new Media;
        try{
            $this->validateStoreRequest($model);
        }
        catch(\Exception $e){
            return $this->onStoreFailure($model, $e);
        }

        \Notify::success("Media has been saved");
        return redirect()->route('media.admin.media');
    }

    protected function modifyEditForm(Form $form, BaseModel $media)
    {
        $form->addDeleteButton(Media::transformUri('confirmDelete', $media, config('core.adminPrefix')));
    }

    protected function onSuccessfullUpdate(BaseModel $model)
    {
        return redirect()->route('media.admin.media');
    }
}
