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
}