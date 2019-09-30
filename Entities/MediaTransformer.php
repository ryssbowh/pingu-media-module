<?php

namespace Pingu\Media\Entities;

use Pingu\Core\Contracts\Models\HasCrudUrisContract;
use Pingu\Core\Entities\BaseModel;
use Pingu\Core\Traits\Models\HasBasicCrudUris;
use Pingu\Core\Traits\Models\HasWeight;
use Pingu\Forms\Support\Fields\TextInput;
use Pingu\Media\Entities\ImageStyle;

class MediaTransformer extends BaseModel implements HasCrudUrisContract
{
    use HasBasicCrudUris, HasWeight;

    protected $fillable = ['class', 'options', 'weight'];

    protected $casts = [
    	'options' => 'json'
    ];

    public static function boot()
    {
        parent::boot();

        static::updated(function($transformer){
            $transformer->image_style->touch();
        });

        static::saving(function($transformer){
            if(is_null($transformer->weight)){
                $transformer->weight = $transformer::getNextWeight(['image_style_id' => $transformer->image_style->id]);
            }
        });
    }

    public static function friendlyName(): string
    {
        return 'Media Transformation';
    }

    public function image_style()
    {
        return $this->belongsTo(ImageStyle::class);
    }

    public function instance()
    {
        $class = $this->class;
        return new $class($this);
    }

    public static function indexUri()
    {
        return ImageStyle::routeSlug().'/{'.ImageStyle::routeSlug().'}/transformations';
    }

    public static function createUri()
    {
        return self::routeSlug().'/{'.ImageStyle::routeSlug().'}/create';
    }

    public static function storeUri()
    {
        return self::routeSlug().'/{'.ImageStyle::routeSlug().'}';
    }

    public static function patchUri()
    {
        return self::routeSlugs().'/{'.ImageStyle::routeSlug().'}';
    }

}
