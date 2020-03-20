<?php 

namespace Pingu\Media\Support\Fields;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Pingu\Core\Entities\BaseModel;
use Pingu\Field\Support\BaseField;
use Pingu\Media\Contracts\UploadsMedias;
use Pingu\Media\Entities\Media as MediaEntity;
use Pingu\Media\Entities\MediaType;
use Pingu\Media\Forms\Fields\UploadMedia;
use Pingu\Media\Traits\UploadsMedias as UploadsMediasTrait;

class Media extends BaseField implements UploadsMedias
{
    use UploadsMediasTrait;

    protected static $availableWidgets = [UploadMedia::class];

    protected $requiredOptions = ['types'];

    protected function init(array $options)
    {
        parent::init($options);
        $this->option('disk', $options['disk'] ?? \Media::getDefaultDisk());
        $this->option('accept', implode(',', $this->getExtensions()));
    }

    /**
     * Gets all the tyeps as classes of MediaType
     * 
     * @return array
     */
    public function getTypes(): array
    {
        return \MediaType::get(Arr::wrap($this->option('types')));
    }

    /**
     * Get all the extensions defined by all the types of medias
     * 
     * @return array
     */
    public function getExtensions(): array
    {
        $extensions = [];
        foreach ($this->getTypes() as $type) {
            $extensions += array_map(function ($ext) {
                return '.'.$ext;
            }, $type->extensions);
        }
        return $extensions;
    }

    /**
     * @inheritDoc
     */
    public function defaultValidationRules(): array
    {
        $imageType = \MediaType::getByName('image');
        return [$this->machineName => 'mimes:'.implode(',', $imageType->extensions)];
    }

    /**
     * @inheritDoc
     */
    public function definesRelation()
    {
        return 'single';
    }

    /**
     * @inheritDoc
     */
    public function castToFormValue($value)
    {
        if ($value) {
            return (string)$value->getKey();
        }
    }

    /**
     * @inheritDoc
     */
    public function castValue($value)
    {
        if ($value) {
            MediaEntity::find($value);
        }
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
    public function filterQueryModifier(Builder $query, $value, BaseModel $model)
    {
        if (!$value) { return;
        }
        $query->where($this->machineName.'_id', '=', $value);
    }
}