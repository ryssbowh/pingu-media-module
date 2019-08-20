<?php

namespace Pingu\Media\Forms\Types;

use Illuminate\Http\UploadedFile;
use Pingu\Core\Entities\BaseModel;
use Pingu\Forms\Support\Types\Model;
use Pingu\Media\Contracts\MediaFieldContract;

class Media extends Model
{
	public function __construct(MediaFieldContract $field)
	{
		parent::__construct($field);
	}

	/**
	 * @inheritDoc
	 */
	public function setModelValue(BaseModel $model, $value)
	{
		if($value instanceof UploadedFile){
			$value = $this->field->uploadFile($value);
		}
		$name = $this->getFieldName();
		$model->$name()->associate($value);
	}
}