<?php

namespace Pingu\Media\Forms\Fields;

use Illuminate\Http\UploadedFile;
use Pingu\Forms\Support\Field;
use Pingu\Forms\Support\Types\Integer;
use Pingu\Media\Contracts\UploadFileContract;
use Pingu\Media\Exceptions\MediaFieldException;

class FileUpload extends Field implements UploadFileContract
{
	public function __construct(string $name, array $options = [], array $attributes = [])
	{
		$options['disk'] = $options['disk'] ?? \Media::getDefaultDisk();
		parent::__construct($name, $options, $attributes);
	}

	public function uploadFile(UploadedFile $file)
	{
		$disk = $this->option('disk');
		$media = \Media::uploadFile($file, $disk);
		return $media;
	}

	public function addValidationRules()
	{
		$extensions = \Media::getAvailableFileExtensions();
		return 'file_extension:'.implode(',', $extensions);
	}

	public static function getDefaultType()
	{
		return Integer::class;
	}
	/**
	 * @inheritDoc
	 */
	public function getDefaultView()
	{
		return 'media::fields.'.$this->getType();
	}
}