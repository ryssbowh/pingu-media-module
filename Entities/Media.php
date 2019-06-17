<?php

namespace Pingu\Media\Entities;

use Pingu\Core\Contracts\Models\HasAdminRoutesContract;
use Pingu\Core\Entities\BaseModel;
use Pingu\Core\Traits\Models\HasAdminRoutes;
use Pingu\Core\Traits\Models\HasAjaxRoutes;
use Pingu\Core\Traits\Models\HasRouteSlug;
use Pingu\Forms\Support\Fields\TextInput;
use Pingu\Forms\Traits\Models\Formable;
use Pingu\Jsgrid\Contracts\Models\JsGridableContract;
use Pingu\Jsgrid\Fields\Media as JsGridMedia;
use Pingu\Jsgrid\Fields\Text as JsGridText;
use Pingu\Jsgrid\Traits\Models\JsGridable;
use Pingu\Media\Entities\MediaType;
use Pingu\Media\Forms\Fields\FileUpload;
use Pingu\Media\Traits\Models\MediaTrait;

class Media extends BaseModel implements JsGridableContract, HasAdminRoutesContract
{
    use MediaTrait, Formable, JsGridable, HasAdminRoutes, HasAjaxRoutes, HasRouteSlug;

    protected $fillable = ['name', 'extension', 'disk', 'size'];

    protected $visible = ['name', 'extension', 'disk', 'size'];

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
    	return [];
    }

    /**
     * inheritDoc
     */
    public function validationRules()
    {
    	return [
            'file' => 'file'
        ];
    }

    /**
     * inheritDoc
     */
    public function validationMessages()
    {
    	return [];
    }
}
