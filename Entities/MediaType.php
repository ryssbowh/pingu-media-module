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
use Pingu\Jsgrid\Fields\ArrayToString;
use Pingu\Jsgrid\Fields\Text;
use Pingu\Jsgrid\Traits\Models\JsGridable;
use Pingu\Media\Traits\Models\MediaTypeTrait;

class MediaType extends BaseModel implements JsGridableContract, HasAdminRoutesContract
{
    use MediaTypeTrait, Formable, JsGridable, HasAdminRoutes, HasAjaxRoutes, HasRouteSlug;

    protected $fillable = ['name', 'machineName', 'extensions'];

    protected $visible = ['id', 'name', 'machineName', 'extensions', 'folder'];

    protected $casts = [
    	'extensions' => 'json'
    ];
    
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
            ]
    	];
    }

    /**
     * inheritDoc
     */
    public function formAddFields()
    {
    	return ['name', 'machineName', 'extensions'];
    }

    /**
     * inheritDoc
     */
    public function formEditFields()
    {
    	return ['name', 'extensions'];
    }

    /**
     * inheritDoc
     */
    public function validationRules()
    {
    	return [
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

}
