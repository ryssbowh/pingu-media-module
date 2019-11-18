<?php

namespace Pingu\Media\Entities\Validators;

use Pingu\Field\Support\FieldValidator\BaseFieldsValidator;

class MediaTransformerValidator extends BaseFieldsValidator
{
    protected function rules(): array
    {
        return [
            'weight' => 'integer'
        ];
    }

    protected function messages(): array
    {
        return [

        ];
    }
}