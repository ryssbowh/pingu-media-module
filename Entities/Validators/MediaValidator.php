<?php

namespace Pingu\Media\Entities\Validators;

use Pingu\Field\Support\FieldValidator\BaseFieldsValidator;

class MediaValidator extends BaseFieldsValidator
{
    protected function rules(bool $updating): array
    {
        return [
            'name' => 'string|required'
        ];  
    }

    protected function messages(): array
    {
        return [

        ];
    }
}