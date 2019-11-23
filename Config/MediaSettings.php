<?php 

namespace Pingu\Media\Config;

use Pingu\Core\Settings\SettingsRepository;
use Pingu\Forms\Support\Fields\NumberInput;

class MediaSettings extends SettingsRepository
{
    /**
     * @inheritDoc
     */
    public function section(): string
    {
        return 'Media';
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'media';
    }

    /**
     * @inheritDoc
     */
    public function accessPermissions(): array 
    {
        return ['view media settings'];
    }

    /**
     * @inheritDoc
     */
    public function editPermissions(): array 
    {
        return ['edit media settings'];
    }

    /**
     * @inheritDoc
     */
    protected function titles(): array
    {
        return [
            'media.maxFileSize' => 'Upload max file size'
        ];
    }

    /**
     * @inheritDoc
     */
    protected function keys(): array
    {
        return ['media.maxFileSize'];
    }

    /**
     * @inheritDoc
     */
    protected function validations(): array
    {
        return [
            'media_maxFileSize' => 'required|integer|min:0|max:'.upload_max_filesize()
        ];
    }

    /**
     * @inheritDoc
     */
    protected function messages(): array
    {
        return [
        ];
    }

    /**
     * @inheritDoc
     */
    protected function units(): array
    {
        return [
            'media.maxFileSize' => 'Kb'
        ];
    }

    /**
     * @inheritDoc
     */
    protected function helpers(): array
    {
        return [
        ];
    }

    /**
     * @inheritDoc
     */
    protected function fields(): array
    {
        return [
            new NumberInput(
                'media.maxFileSize',
                [
                    'label' => $this->getFieldLabel('media.maxFileSize'),
                    'required' => true
                ],
                [
                    'max' => upload_max_filesize()
                ]
            )
        ];
    }
}