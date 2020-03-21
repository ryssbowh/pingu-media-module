<?php

namespace Pingu\Media\Entities\Validators;

use Pingu\Field\Support\FieldValidator\BaseFieldsValidator;

class MediaTypeValidator extends BaseFieldsValidator
{
    protected function rules(bool $updating): array
    {
        return [
            'icon' => 'file',
            'extensions' => 'required|regex:/^\w+(,\w+)*$/i|unique_extensions:'.$this->object->id,
            'name' => 'required',
            'machineName' => 'required|unique:media_types,machineName,'.$this->object->id
        ];
    }

    protected function messages(): array
    {
        return [

        ];
    }
}