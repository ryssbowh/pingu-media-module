<?php

namespace Pingu\Media\Entities;

use Illuminate\Support\Arr;
use Pingu\Field\Entities\BaseBundleField;
use Pingu\Field\Traits\HandlesModel;
use Pingu\Forms\Support\Fields\TextInput;
use Pingu\Media\Contracts\UploadsMedias;
use Pingu\Media\Entities\Media;
use Pingu\Media\Forms\Fields\UploadMedia;
use Pingu\Media\Traits\UploadsMedias as UploadsMediasTrait;

class FieldMedia extends BaseBundleField implements UploadsMedias
{
    use UploadsMediasTrait, HandlesModel;

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
    public function filterable(): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    protected function getModel(): string
    {
        return Media::class;
    }

    /**
     * @inheritDoc
     */
    public function defaultValue()
    {
        return null;
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
}