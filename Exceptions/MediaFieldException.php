<?php

namespace Pingu\Media\Exceptions;

use Pingu\Forms\Support\Field;
use Pingu\Media\Contracts\UploadFileContract;

class MediaFieldException extends \Exception
{
	public static function diskNotDefined(string $name, string $disk)
	{
		return new static("Field '$name' : disk '$disk' is not defined in this application");
	}

	public static function fieldCantUpload(string $name, Field $field)
	{
		return new static("Field '$name' must implement ".UploadFileContract::class.' to upload files';
	}
}