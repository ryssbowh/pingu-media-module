<?php

namespace Pingu\Media\Transformers;

use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;
use Intervention\Image\ImageManagerStatic as Image;

class Resize extends ImageTransformer
{
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