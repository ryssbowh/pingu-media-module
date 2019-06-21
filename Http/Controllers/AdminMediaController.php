<?php

namespace Pingu\Media\Http\Controllers;

use Pingu\Core\Entities\BaseModel;
use Pingu\Core\Http\Controllers\AdminModelController;
use Pingu\Forms\Support\Form;
use Pingu\Media\Entities\Media;

class MediaController extends AdminModelController
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
        $form->addDeleteButton(Media::transformAdminUri('confirmDelete', $media, true));
    }

    protected function onSuccessfullUpdate(BaseModel $model)
    {
        return redirect()->route('media.admin.media');
    }
}
