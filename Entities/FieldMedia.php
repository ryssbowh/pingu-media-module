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
    public function castSingleValueToDb($value)
    {
        return $value->id;
    }

    /**
     * @inheritDoc
     */
    public function castToSingleFormValue($value)
    {
        if ($value) {
            return $value->id;
        }
    }

    /**
     * @inheritDoc
     */
    public function castSingleValueFromDb($value)
    {
        if ($value) {
            return (int)$value;
        }
    }

    /**
     * @inheritDoc
     */
    public function castSingleValue($value)
    {
        if ($value) {
            return Media::find($value);
        }
    }

    /**
     * Types accessor
     * 
     * @param $value
     * 
     * @return array
     */
    public function getTypesAttribute($value)
    {
        return array_map(function ($name) {
            return MediaType::findByMachineName($name);
        }, json_decode($value));
    }

    /**
     * Get all available extensions for this field
     * 
     * @return array
     */
    public function getExtensions($withDots = false)
    {
        $out = [];
        foreach ($this->types as $type) {
            $out = array_merge($out, array_map(function ($ext) use ($withDots){
                return $withDots ? '.'.$ext : $ext;
            }, $type->extensions));
        }
        return $out;
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
                'accept' => implode(',', $this->getExtensions(true)),
                'default' => $value
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function defaultValidationRule(): string
    {
        return ($this->required ? 'required|' : '') . 'file|mimes:'.implode(',', $this->getExtensions()).'|max:'.$this->getMaxFileSize();
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
}