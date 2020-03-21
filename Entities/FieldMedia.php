<?php

namespace Pingu\Media\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Pingu\Entity\Entities\Entity;
use Pingu\Field\Entities\BaseBundleField;
use Pingu\Forms\Support\Field;
use Pingu\Forms\Support\Fields\TextInput;
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

    protected static $availableFilterWidgets = [TextInput::class];
    
    protected $fillable = ['required', 'types'];

    protected $casts = [
        'required' => 'boolean',
        'types' => 'json'
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
        $value = Arr::wrap(json_decode($value, true));
        return array_map(function ($name) {
            return \MediaType::getByName($name);
        }, $value);
    }

    public function formTypesAttribute($value)
    {
        return Arr::wrap(json_decode($value, true));
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
    public function formFieldOptions(int $index = 0): array
    {
        return [
            'required' => $this->required,
            'accept' => implode(',', $this->getExtensions(true))
        ];
    }

    /**
     * @inheritDoc
     */
    public function defaultValidationRule(): string
    {
        return ($this->required ? 'required|' : 'sometimes|') . 'file|mimes:'.implode(',', $this->getExtensions()).'|max:'.$this->getMaxFileSize();
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

    /**
     * @inheritDoc
     */
    public function singleFilterQueryModifier(Builder $query, $value, Entity $entity)
    {
        $query->where('value', '=', $value);
    }
}