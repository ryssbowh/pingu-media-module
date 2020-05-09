<?php

namespace Pingu\Media\Http\Controllers;

use Pingu\Media\Entities\Media;
use Pingu\Media\Http\Requests\CreateMediaRequest;

trait StoresMedia
{
    public function store(CreateMediaRequest $request)
    {
        try{
            $this->beforeStore();
            $validated = $request->validated();
            $media = $this->performStore($validated);
            $this->afterStoreSuccess($media);
        }
        catch(\Exception $e){
            return $this->onStoreFailure($e);
        }

        return $this->onStoreSuccess($media);
    }
    /**
     * Store the entity
     * 
     * @param Entity $entity
     * @param array  $validated
     */
    protected function performStore(array $validated)
    {
        return \Media::uploadFile($validated['file']);
    }

    abstract protected function onStoreFailure(\Exception $e);

    abstract protected function onStoreSuccess(Media $media);
    
}