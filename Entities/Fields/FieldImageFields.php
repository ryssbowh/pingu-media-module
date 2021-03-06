<?php

namespace Pingu\Media\Entities\Fields;

use Pingu\Field\BaseFields\Boolean;
use Pingu\Field\BaseFields\Integer;
use Pingu\Field\BaseFields\_List;
use Pingu\Field\Support\FieldRepository\BundleFieldFieldRepository;
use Pingu\Media\Entities\MediaType;

class FieldImageFields extends BundleFieldFieldRepository
{
    /**
     * @inheritDoc
     */
    protected function fields(): array
    {
        return [
            new Boolean('required'),
            new _List('types', [
                'items' => MediaType::all()->pluck('name', 'machineName')->toArray(),
                'multiple' => true,
                'required' => true
            ])
        ];
    }

    /**
     * @inheritDoc
     */
    protected function rules(): array
    {
        return [
            'required' => 'boolean',
            'types' => 'array',
            'types.*' => 'string|exists:media_types,machineName'
        ];
    }

    /**
     * @inheritDoc
     */
    protected function messages(): array
    {
        return [];
    }
}