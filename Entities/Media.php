<?php

namespace Pingu\Media\Entities;

use Pingu\Core\Entities\BaseModel;
use Pingu\Core\Traits\Models\HasBasicCrudUris;
use Pingu\Forms\Support\Fields\TextInput;
use Pingu\Forms\Traits\Models\Formable;
use Pingu\Jsgrid\Contracts\Models\JsGridableContract;
use Pingu\Jsgrid\Fields\Media as JsGridMedia;
use Pingu\Jsgrid\Fields\Text as JsGridText;
use Pingu\Jsgrid\Traits\Models\JsGridable;
use Pingu\Media\Entities\MediaType;
use Pingu\Media\Forms\Fields\FileUpload;
use Pingu\Media\Traits\Models\MediaTrait;

class Media extends BaseModel implements JsGridableContract
{
    use MediaTrait, Formable, JsGridable, HasBasicCrudUris;

    protected $fillable = ['name', 'extension', 'disk', 'size'];

    protected $visible = ['id', 'name', 'extension', 'disk', 'size'];

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
            'disk' => [
                'field' => TextInput::class
            ],
            'size' => [
                'field' => TextInput::class
            ],
    		'file' => [
    			'field' => FileUpload::class,
                'attributes' => [
                    'required' => true
                ]
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
    			'type' => JsGridText::class
    		],
            'disk' => [
                'type' => JsGridText::class,
                'options' => [
                    'editing' => false
                ]
            ],
            'size' => [
                'type' => JsGridText::class,
                'options' => [
                    'editing' => false
                ]
            ],
            'image' => [
                'type' => JsGridMedia::class,
                'options' => [
                    'title' => 'Preview'
                ]
            ]
    	];
    }

    public function getJsGridImageField()
    {
        return $this->url('icon');
    }

    /**
     * inheritDoc
     */
    public function formAddFields()
    {
    	return ['file'];
    }

    /**
     * inheritDoc
     */
    public function formEditFields()
    {
    	return ['name'];
    }

    /**
     * inheritDoc
     */
    public function validationRules()
    {
    	return [
            'file' => 'required|max:'.config('media.maxFileSize'),
            'name' => 'required|unique_media_name:'.$this->id
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
}
