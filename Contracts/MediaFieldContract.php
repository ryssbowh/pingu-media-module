<?php

namespace Pingu\Media\Contracts;

use Illuminate\Http\UploadedFile;

interface MediaFieldContract
{
	public function uploadFile(UploadedFile $file);

	public function getMedia();
}