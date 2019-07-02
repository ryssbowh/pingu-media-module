<?php

namespace Pingu\Media\Forms\Types;

use Pingu\Core\Entities\BaseModel;
use Pingu\Forms\Support\Type;
use Pingu\Media\Contracts\UploadFileContract;

class AnyType extends Type implements UploadFileContract
{
	public static function uploadFile(BaseModel $model, string $field, $value, array $definition)
	{
		$media = \Media::uploadMedia($value, $definition['disk'] ?? null);
	}

	
}