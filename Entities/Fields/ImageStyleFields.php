<?php

namespace Pingu\Media\Entities\Fields;

use Pingu\Field\BaseFields\Text;
use Pingu\Field\Support\FieldRepository\BaseFieldRepository;

class ImageStyleFields extends BaseFieldRepository
{
    protected function fields(): array
    {
        return [
            new Text('name'),
            new Text('description'),
            new Text(
                'machineName',
                [
                    'label' => 'Machine Name'
                ]
            )
        ];
    }

    protected function rules(): array
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