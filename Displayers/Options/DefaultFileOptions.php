<?php

namespace Pingu\Media\Displayers\Options;

use Pingu\Field\Support\DisplayOptions;
use Pingu\Forms\Support\Fields\Select;
use Pingu\Forms\Support\Fields\TextInput;

class DefaultFileOptions extends DisplayOptions
{
    /**
     * Options defined by this class
     * @var array
     */
    protected $optionNames = ['label', 'custom'];

    /**
     * @var array
     */
    protected $labels = [
        'label' => 'Label',
        'custom' => 'Custom'
    ];

    /**
     * @var array
     */
    protected $values = [
        'label' => 'file',
        'custom' => '',
    ];

    /**
     * @inheritDoc
     */
    public function toFormElements(): array
    {
        return [
            new Select('label', [
                'default' => $this->label,
                'items' => [
                    'file' => 'File name',
                    'custom' => 'Custom (fill underneath)'
                ]
            ]),
            new TextInput('custom', [
                'default' => $this->custom
            ])
        ];
    }

    /**
     * @inheritDoc
     */
    public function friendlyDescription(): string
    {
        if ($this->label == 'custom') {
            return '<p>'.$this->label('label').': '.$this->custom.'</p>';
        } else {
            return '<p>'.$this->label('label').': from file name</p>';
        }
    }

    /**
     * @inheritDoc
     */
    public function getValidationRules(): array
    {
        return [
            'label' => 'string|required|in:file,custom',
            'custom' => 'string|required_if:label,custom'
        ];
    }
}