<?php

namespace Pingu\Media\Observers;

use Pingu\Media\Entities\Media;
use Pingu\Media\Exceptions\MediaException;

class MediaObserver
{
    /**
     * Creates media styles
     * 
     * @param Media $media
     */
    public function created(Media $media)
    {
        if (config('media.stylesCreationStrategy', 'lazy') == 'eager') {
            $media->createStyles();
        }
    }

    /**
     * Delete media file
     * 
     * @param Media $media
     */
    public function deleted(Media $media)
    {
        $media->deleteFile();
    }
}