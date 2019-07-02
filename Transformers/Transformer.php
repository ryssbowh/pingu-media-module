<?php

namespace Pingu\Media\Transformers;

abstract class Transformer
{
	protected $options;

	public function __construct(array $options)
	{
		$this->options = $options;
	}
}