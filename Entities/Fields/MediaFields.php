<?php

namespace Pingu\Media\Entities\Fields;

use Pingu\Entity\Support\FieldRepository\BundledEntityFieldRepository;
use Pingu\Field\BaseFields\Model;
use Pingu\Field\BaseFields\Text;
use Pingu\Media\Entities\MediaType;

class MediaFields extends BundledEntityFieldRepository
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
            new Text('disk', [
                'disabled' => true
            ]),
            new Text('size', [
                'disabled' => true
            ]),
            new Text(
                'filename',
                [
                    'required' => true,
                    'disabled' => true
                ]
            ),
            new Model(
                'media_type',
                [
                    'model' => MediaType::class,
                    'textField' => 'name',
                    'required' => true,
                    'disabled' => true
                ]
            )
        ];
    }
}