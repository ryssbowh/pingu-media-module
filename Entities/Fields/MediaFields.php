<?php

namespace Pingu\Media\Entities\Fields;

use Pingu\Field\BaseFields\Text;
use Pingu\Field\Support\FieldRepository\BaseFieldRepository;

class MediaFields extends BaseFieldRepository
{
    protected function fields(): array
    {
        return [
            new Text(
                'name',
                [
                    'required' => true
                ]
            ),
            new Text('disk'),
            new Text('size'),
            new Text(
                'filename',
                [
                    'required' => true
                ]
            )
        ];
    }
}