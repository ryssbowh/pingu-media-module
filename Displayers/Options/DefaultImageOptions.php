<?php

namespace Pingu\Media\Displayers\Options;

use Pingu\Entity\Support\Entity;
use Pingu\Field\Contracts\FieldDisplayerContract;
use Pingu\Field\Support\DisplayOptions;
use Pingu\Forms\Support\Fields\Select;
use Pingu\Media\Entities\Image;

class DefaultImageOptions extends DisplayOptions
{
    /**
     * Options defined by this class
     * @var array
     */
    protected $optionNames = ['linkTo', 'style'];

    /**
     * @var array
     */
    protected $labels = [
        'linkTo' => 'Link to',
        'style' => 'Style'
    ];

    /**
     * @var array
     */
    protected $values = [
        'linkTo' => 'no',
        'style' => '_full',
    ];

    protected $linkToValues = [
        'no' => 'No link',
        'image' => 'Image',
        'entity' => 'Entity',
    ];

    protected $styleValues = [];

    /**
     * @inheritDoc
     */
    public function __construct(FieldDisplayerContract $displayer)
    {
        parent::__construct($displayer);
        $this->styleValues = $this->getStyles();
    }

    /**
     * @inheritDoc
     */
    public function toFormElements(): array
    {
        return [
            new Select('linkTo', [
                'default' => $this->linkTo,
                'items' => $this->linkToValues
            ]),
            new Select('style', [
                'default' => $this->style,
                'items' => $this->styleValues
            ])
        ];
    }

    /**
     * @inheritDoc
     */
    public function friendlyValue(string $key)
    {
        if ($key == 'linkTo') {
            return $this->linkToValues[$this->values[$key]];
        }
        if ($key == 'style') {
            return $this->styleValues[$this->values[$key]];
        }
        return $this->values[$key];
    }

    /**
     * Get all image styles as array
     * 
     * @return array
     */
    protected function getStyles(): array
    {
        $styles = [
            '_full' => 'Full Image'
        ];
        foreach (\ImageStyle::all() as $style) {
            $styles[$style->machineName] = $style->name;
        }
        return $styles;
    }

    /**
     * @inheritDoc
     */
    public function getValidationRules(): array
    {
        return [
            'linkTo' => 'string|in:'.implode(',', array_keys($this->linkToValues)),
            'style' => 'string|in:'.implode(',', array_keys($this->styleValues))
        ];
    }

    /**
     * Get the link
     * 
     * @param Entity $entity
     * @param Image  $image
     * 
     * @return string 
     */
    public function getLink(Entity $entity, Image $image)
    {
        if ($this->linkTo == 'entity') {
            return $entity::uris()->make('view', $entity);
        } else {
            return $image->url();
        }
    }
}