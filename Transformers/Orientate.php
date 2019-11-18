<?php

namespace Pingu\Media\Transformers;

use Intervention\Image\ImageManagerStatic as Image;
use Pingu\Media\Contracts\TransformerContract;
use Pingu\Media\Traits\Transformer;

class Orientate implements TransformerContract
{
    use Transformer;

    /**
     * Process the image resizing according to options.
     * Will keep the image ratio
     * 
     * @param  string $file
     * @return bool
     */
    public function process(string $file)
    {
        if(!file_exists($file)){
            return false;
        }
        $img = Image::make($file)->orientate()->save($file);
        return true;
    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return 'Orientate the image correctly';
    }

    /**
     * @inheritDoc
     */
    public static function getName()
    {
        return 'Orientate';
    }
}