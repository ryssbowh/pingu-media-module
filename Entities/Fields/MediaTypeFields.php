<?php

namespace Pingu\Media\Entities\Fields;

use Pingu\Field\BaseFields\Text;
use Pingu\Field\Support\FieldRepository\BaseFieldRepository;
use Pingu\Media\Support\Fields\Media;

class MediaTypeFields extends BaseFieldRepository
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
            new Text(
                'machineName',
                [
                    'required' => true,
                    'class' => 'js-dashify',
                    'data-dashifyfrom' => 'name'
                ]
            ),
            new Text(
                'extensions',
                [
                    'helper' => 'List of extensions separated by commas. Do not include the dot',
                    'required' => true
                ]
            ),
            new Media(
                'icon',
                [
                    'types' => 'image'
                ]
            )
        ];
    }
}