<?php

namespace Pingu\Media\Http\Controllers;

use Pingu\Core\Http\Controllers\BaseController;
use Pingu\Entity\Entities\Entity;
use Pingu\Entity\Traits\Controllers\Entities\CreatesAdminEntity;
use Pingu\Entity\Traits\Controllers\Entities\DeletesAdminEntity;
use Pingu\Entity\Traits\Controllers\Entities\EditsAdminEntity;
use Pingu\Entity\Traits\Controllers\Entities\IndexesAdminEntity;
use Pingu\Entity\Traits\Controllers\Entities\PatchesAdminEntity;
use Pingu\Entity\Traits\Controllers\Entities\UpdatesAdminEntity;
use Pingu\Media\Entities\Media;

class MediaAdminController extends BaseController
{
    use EditsAdminEntity, 
        UpdatesAdminEntity, 
        CreatesAdminEntity, 
        DeletesAdminEntity, 
        PatchesAdminEntity, 
        IndexesAdminEntity,
        StoresMedia;

    protected function onStoreSuccess(Media $media)
    {
        return redirect()->route('media.admin.media');
    }

    protected function onStoreFailure(\Exception $exception)
    {
        if(env('APP_ENV') == 'local'){
            throw $exception;
        }
        \Notify::danger('Error while creating '.Media::friendlyName());
        return back();
    }

    protected function afterStoreSuccess(Media $media)
    {
        \Notify::success(Media::friendlyName().' created');
    }
}
