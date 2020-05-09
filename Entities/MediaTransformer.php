<?php

namespace Pingu\Media\Entities;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Pingu\Core\Entities\BaseModel;
use Pingu\Core\Traits\Models\HasWeight;
use Pingu\Entity\Support\Entity;
use Pingu\Forms\Contracts\FormRepositoryContract;
use Pingu\Forms\Support\Fields\TextInput;
use Pingu\Media\Entities\Forms\MediaTransformerForms;
use Pingu\Media\Entities\ImageStyle;

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

    public static function forms(): FormRepositoryContract
    {
        return new MediaTransformerForms;
    }

    /**
     * @inheritDoc
     */
    public static function friendlyName(): string
    {
        return 'Media Transformation';
    }

    /**
     * Image style relation
     * 
     * @return BelongsTo
     */
    public function image_style()
    {
        return $this->belongsTo(ImageStyle::class);
    }

    /**
     * Instance of this transformer
     * 
     * @return 
     */
    public function instance()
    {
        $class = $this->class;
        return new $class($this);
    }
}
