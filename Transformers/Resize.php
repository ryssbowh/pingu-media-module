<?php

namespace Pingu\Media\Transformers;

use Intervention\Image\ImageManagerStatic as Image;
use Pingu\Forms\Support\Fields\NumberInput;
use Pingu\Media\Contracts\TransformerWithOptionsContract;
use Pingu\Media\Traits\HasTransformerOptions;
use Pingu\Media\Traits\Transformer;

class Resize implements TransformerWithOptionsContract
{
    use Transformer;

    /**
     * @inheritDoc
     */
    public static function hasOptions()
    {
        return true;
    }

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
        $img = Image::make($file);
        $width = $this->options['width'] ?? null;
        $height = $this->options['height'] ?? null;
        $res = $img->resize($width, $height, function($c){
            $c->aspectRatio();
        })->save($file);
        return true;
    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        $width = $this->options['width'] ? $this->options['width'] : 'auto';
        $height = $this->options['height'] ? $this->options['height'] : 'auto';
        return 'Resize '.$width.' x '.$height;
    }

    /**
     * @inheritDoc
     */
    public static function getName()
    {
        return 'Resize';
    }

    /**
     * @inheritDoc
     */
    public function getOptionsFields()
    {
        return [
            new NumberInput('width'),
            new NumberInput('height')
        ];
    }

    /**
     * @inheritDoc
     */
    public function getValidationRules()
    {
        return [
            'width' => 'required_without:height|integer',
            'height' => 'required_without:width|integer'
        ];
    }

    /**
     * @inheritDoc
     */
    public function getValidationMessages()
    {
        return [];
    }
}