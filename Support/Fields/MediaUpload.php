<?php 

namespace Pingu\Media\Support\Fields;

use Illuminate\Http\UploadedFile;
use Pingu\Field\Support\BaseField;
use Pingu\Forms\Support\Fields\SelectMedia;
use Pingu\Media\Contracts\UploadsFiles;
use Pingu\Media\Entities\Media;

class MediaUpload extends BaseField implements UploadsFiles
{
    public function __construct(string $machineName, array $options = [], ?string $name = null, ?string $formFieldClass = null)
    {
        $options['disk'] = $options['disk'] ?? \Media::getDefaultDisk();
        parent::__construct($machineName, $options, $name, $formFieldClass);
    }

    public function definesRelation()
    {
        return 'single';
    }

    protected function defaultFormFieldClass(): string
    {
        return SelectMedia::class;
    }

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
        return Media::findOrFail($value);
    }

    public function uploadFile(UploadedFile $file): string
    {
        return \Media::uploadFile($file, $this->option('disk'))->id;
    }
}