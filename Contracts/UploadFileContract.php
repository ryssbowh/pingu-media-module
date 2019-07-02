<?php

namespace Pingu\Media\Contracts;

use Illuminate\Http\UploadedFile;

interface UploadFileContract
{
	public function uploadFile(UploadedFile $file);
}