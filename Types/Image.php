<?php

namespace Pingu\Media\Types;

use Pingu\Media\Contracts\MediaTypeContract;

class Image implements MediaTypeContract
{
	public function folder()
	{
		return 'images';
	}
}