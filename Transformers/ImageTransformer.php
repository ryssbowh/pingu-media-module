<?php

namespace Pingu\Media\Transformers;

use Pingu\Media\Exceptions\MediaStyleException;

abstract class ImageTransformer
{
	protected $library;
	protected $options;

	public function __construct(array $options)
	{
		$this->options = $options;
	}
}