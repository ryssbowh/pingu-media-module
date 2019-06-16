<?php

namespace Pingu\Media\Entities;

use Pingu\Core\Entities\BaseModel;
use Pingu\Media\Traits\Models\MediaTypeTrait;

class MediaType extends BaseModel
{
    use MediaTypeTrait;

    protected $fillable = [];

    protected $casts = [
    	'extensions' => 'json'
    ];

    
}
