<?php

namespace Pingu\Media\Entities\Validators;

use Pingu\Field\Support\FieldValidator\BaseFieldsValidator;

class ImageStyleValidator extends BaseFieldsValidator
{
    protected function rules(bool $updating): array
    {
        return [
            'name' => 'required',
            'description' => 'string',
            'machineName' => 'required|unique:image_styles,machineName'
        ];
    }

    protected function messages(): array
    {
        return [

        ];
    }
}