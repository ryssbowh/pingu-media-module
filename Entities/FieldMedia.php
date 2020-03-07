<?php

namespace Pingu\Media\Entities;

use Illuminate\Http\UploadedFile;
use Pingu\Field\Entities\BaseBundleField;
use Pingu\Forms\Support\Field;
use Pingu\Media\Contracts\UploadsMedias;
use Pingu\Media\Entities\Media;
use Pingu\Media\Entities\MediaType;
use Pingu\Media\Forms\Fields\UploadMedia;
use Pingu\Media\Traits\UploadsMedias as UploadsMediasTrait;

class FieldMedia extends BaseBundleField implements UploadsMedias
{
    use UploadsMediasTrait;

    protected $table = 'field_medias';
    
    protected static $availableWidgets = [UploadMedia::class];
    
    protected $fillable = ['required', 'types'];

    protected $casts = [
        'required' => 'boolean',
        'types' => 'array'
    ];

    /**
     * @inheritDoc
     */
    public function defaultValue()
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function singleFormValue($value)
    {
        if ($value instanceof Media) {
            return $value->id;
        }
        return $value;
    }

    /**
     * @inheritDoc
     */
    public function castSingleValue($value)
    {
        return Media::find($value);
    }
    
    /**
     * @inheritDoc
     */
    public function toSingleFormField($value): Field
    {
        return new UploadMedia(
            $this->machineName(),
            [
                'label' => $this->name(),
                'showLabel' => false,
                'required' => $this->required,
                'types' => $this->types,
                'default' => $value
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function defaultValidationRule(): string
    {
        return ($this->required ? 'required|' : '') . 'file|mimes:'.$this->getExtensions().'|max:'.$this->getMaxFileSize();
    }

    /**
     * @inheritDoc
     */
    public function getDisk()
    {
        return config('media.defaultDisk');
    }

    /**
     * @inheritDoc
     */
    protected function getMaxFileSize()
    {
        return config('media.maxFileSize');
    }

    protected function getExtensions()
    {
        $extensions = [];
        foreach ($this->types as $type) {
            $type = MediaType::findByMachineName($type);
            $extensions = array_merge($extensions, $type->extensions);
        }
        return implode(',', $extensions);
    }
}