<?php

namespace Pingu\Media\Entities;

use Pingu\Core\Entities\BaseModel;
use Pingu\Core\Traits\Models\HasBasicCrudUris;
use Pingu\Forms\Support\Fields\MediaUpload;
use Pingu\Forms\Support\Fields\TextInput;
use Pingu\Forms\Traits\Models\Formable;
use Pingu\Jsgrid\Contracts\Models\JsGridableContract;
use Pingu\Jsgrid\Fields\ArrayToString;
use Pingu\Jsgrid\Fields\Media;
use Pingu\Jsgrid\Fields\Text;
use Pingu\Jsgrid\Traits\Models\JsGridable;
use Pingu\Media\Entities\Media as MediaModel;

class MediaType extends BaseModel implements JsGridableContract
{
    use Formable, JsGridable, HasBasicCrudUris;

    protected $fillable = ['name', 'machineName', 'extensions', 'icon'];

    protected $visible = ['id', 'name', 'machineName', 'extensions', 'icon'];

    protected $casts = [
    	'extensions' => 'json'
    ];

    protected $with = ['icon'];

    public static function getByExtension(string $ext)
    {
        return static::whereJsonContains('extensions', $ext)->first();
    }

    /**
     * Medias relationship
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function medias()
    {
        return $this->hasMany(Media::class);
    }

    /**
     * Icon relationship
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function icon()
    {
        return $this->belongsTo(MediaModel::class);
    }

    /**
     * Returns the icon url for that type.
     * If the file doesn't exist on disk, return a placeholder
     * 
     * @return 
     */
    public function urlIcon()
    {
        if(!$this->icon or !$this->icon->fileExists()){
            return module_url('Media', 'not_found.jpg');
        }
        return $this->icon->url();
    }
    
    /**
     * inheritDoc
     */
    public function fieldDefinitions()
    {
    	return [
    		'name' => [
    			'field' => TextInput::class,
                'attributes' => [
                    'required' => true
                ]
    		],
            'machineName' => [
    			'field' => TextInput::class,
                'attributes' => [
                    'required' => true,
                    'class' => 'js-dashify',
                    'data-dashifyfrom' => 'name'
                ]
    		],
    		'extensions' => [
    			'field' => TextInput::class,
    			'options' => [
    				'helper' => 'List of extensions separated by commas. Do not include the dot'
    			],
                'attributes' => [
                    'required' => true
                ]
    		],
            'icon' => [
                'field' => MediaUpload::class
            ]
    	];
    }

    /**
     * inheritDoc
     */
    public function jsGridFields()
    {
    	return [
    		'name' => [
    			'type' => Text::class
    		],
            'extensions' => [
                'type' => ArrayToString::class
            ],
            'icon' => [
                'type' => Media::class
            ]
    	];
    }

    /**
     * inheritDoc
     */
    public function formAddFields()
    {
    	return ['name', 'machineName', 'extensions', 'icon'];
    }

    /**
     * inheritDoc
     */
    public function formEditFields()
    {
    	return ['name', 'extensions', 'icon'];
    }

    /**
     * inheritDoc
     */
    public function validationRules()
    {
    	return [
            'icon' => 'file',
            'extensions' => 'required|regex:/^\w+(,\w+)*$/i|unique_extensions:'.$this->id,
            'name' => 'required',
            'machineName' => 'required|unique:media_types,machineName,'.$this->id
        ];
    }

    /**
     * inheritDoc
     */
    public function validationMessages()
    {
    	return [
            'name.unique_media_name' => 'This name is already in use'
        ];
    }

    /**
     * Transform extensions when passing to a field
     * 
     * @param  string $value
     * @return string
     */
    public function formExtensionsAttribute(string $value)
    {
        return implode(',', $this->extensions);
    }

}
