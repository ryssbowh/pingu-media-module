<?php

namespace Pingu\Media\Entities\Validators;

use Pingu\Field\Support\FieldValidator\BaseFieldsValidator;

class MediaValidator extends BaseFieldsValidator
{
    protected function rules(): array
    {
        return [
            'file' => 'required|max:'.config('media.maxFileSize')
        ];  
    }

    protected function messages(): array
    {
        return [

        ];
    }
}