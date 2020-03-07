<?php 

namespace Pingu\Media\Support\Fields;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Pingu\Field\Support\BaseField;
use Pingu\Forms\Support\Fields\SelectMedia;
use Pingu\Media\Contracts\UploadsMedias;
use Pingu\Media\Entities\Media as MediaEntity;
use Pingu\Media\Entities\MediaType;
use Pingu\Media\Forms\Fields\UploadMedia;
use Pingu\Media\Traits\UploadsMedias as UploadsMediasTrait;

class Media extends BaseField implements UploadsMedias
{
    use UploadsMediasTrait;

    protected static $availableWidgets = [UploadMedia::class];

    protected $requiredOptions = ['type'];

    protected function init(array $options)
    {
        $options['disk'] = $options['disk'] ?? \Media::getDefaultDisk();
        foreach (Arr::wrap($options['type']) as $typeName) {
            $type = MediaType::findByMachineName($typeName);
            $extensions = array_map(
                function ($ext) {
                    return '.'.$ext;
                }, $type->extensions
            );
        }
        $options['accept'] = implode(',', $extensions);
        parent::init($options);
    }

    /**
     * @inheritDoc
     */
    public function defaultValidationRules(): array
    {
        $imageType = MediaType::findByMachineName('image');
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
    public function formValue($value)
    {
        if ($value) {
            return (string)$value->getKey();
        }
        return null;
    }

    /**
     * @inheritDoc
     */
    public function castValue($value)
    {
        if (!$value) {
            return null;
        }
        return MediaEntity::findOrFail($value);
    }

    /**
     * @inheritDoc
     */
    public function getDisk()
    {
        return config('media.defaultDisk');
    }
}