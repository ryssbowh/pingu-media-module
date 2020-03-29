<?php

namespace Pingu\Media\Entities;

use Pingu\Core\Entities\BaseModel;
use Pingu\Core\Traits\Models\HasWeight;
use Pingu\Entity\Support\Entity;
use Pingu\Forms\Contracts\FormRepositoryContract;
use Pingu\Forms\Support\Fields\TextInput;
use Pingu\Media\Entities\Forms\MediaTransformerForms;
use Pingu\Media\Entities\ImageStyle;
use Pingu\Media\Entities\Policies\MediaTransformerPolicy;

class MediaTransformer extends Entity
{
    use HasWeight;

    protected $fillable = ['class', 'options', 'weight'];

    protected $casts = [
        'options' => 'json'
    ];

    public static function boot()
    {
        parent::boot();

        static::updated(
            function ($transformer) {
                $transformer->image_style->touch();
            }
        );

        static::saving(
            function ($transformer) {
                if (is_null($transformer->weight)) {
                    $transformer->weight = $transformer::getNextWeight(['image_style_id' => $transformer->image_style->id]);
                }
            }
        );
    }

    public function forms(): FormRepositoryContract
    {
        return new MediaTransformerForms($this);
    }

    public function getPolicy(): string
    {
        return MediaTransformerPolicy::class;
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
}
