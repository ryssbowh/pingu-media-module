<?php 
namespace Pingu\Media\Contracts;

use Pingu\Forms\Contracts\Models\FormableContract;
use Pingu\Forms\Support\Form;
use Pingu\Media\Entities\ImageStyle;
use Pingu\Media\Entities\MediaTransformer;

interface TransformerWithOptionsContract extends TransformerContract
{
	public function getOptionsDefinitions();

	public function getValidationRules();

	public function getValidationMessages();

	public function getOption($key);
}