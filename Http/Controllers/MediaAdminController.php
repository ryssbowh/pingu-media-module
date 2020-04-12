<?php

namespace Pingu\Media\Http\Controllers;

use Pingu\Core\Http\Controllers\BaseController;
use Pingu\Entity\Support\Entity;
use Pingu\Entity\Traits\Controllers\Entities\CreatesAdminEntity;
use Pingu\Entity\Traits\Controllers\Entities\DeletesAdminEntity;
use Pingu\Entity\Traits\Controllers\Entities\EditsAdminEntity;
use Pingu\Entity\Traits\Controllers\Entities\IndexesAdminEntity;
use Pingu\Entity\Traits\Controllers\Entities\PatchesAdminEntity;
use Pingu\Entity\Traits\Controllers\Entities\UpdatesAdminEntity;
use Pingu\Entity\Traits\Controllers\RendersEntityViews;
use Pingu\Forms\Support\Form;
use Pingu\Media\Contracts\MediaContract;

class MediaAdminController extends BaseController
{
    use EditsAdminEntity, 
        UpdatesAdminEntity, 
        CreatesAdminEntity, 
        DeletesAdminEntity, 
        PatchesAdminEntity, 
        IndexesAdminEntity,
        StoresMedia,
        RendersEntityViews;

    /**
     * @inheritDoc
     */
    protected function onStoreSuccess(MediaContract $media)
    {
        return redirect()->route('media.admin.index');
    }

    /**
     * @inheritDoc
     */
    protected function onStoreFailure(\Exception $exception)
    {
        if(env('APP_ENV') == 'local') {
            throw $exception;
        }
        \Notify::danger('Error while creating '.Media::friendlyName());
        return back();
    }

    /**
     * @inheritDoc
     */
    protected function afterStoreSuccess(MediaContract $media)
    {
        \Notify::success($media::friendlyName().' created');
    }

    /**
     * @inheritDoc
     */
    protected function afterEditFormCreated(Form $form, Entity $entity)
    {
        $form->getElement('filename')->option('disabled', true);
        $form->getElement('size')->option('disabled', true);
        $form->getElement('disk')->option('disabled', true);
    }
}
