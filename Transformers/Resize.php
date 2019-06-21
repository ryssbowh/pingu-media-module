<?php

namespace Pingu\Media\Transformers;

use Intervention\Image\ImageManagerStatic as Image;

class Resize extends Transformer
{
	/**
	 * Process the image resizing according to options.
	 * Will keep the image ration
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
}