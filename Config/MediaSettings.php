<?php 

namespace Pingu\Media\Config;

use Pingu\Core\Settings\SettingsRepository;
use Pingu\Field\BaseFields\Integer;
use Pingu\Field\BaseFields\_List;

class MediaSettings extends SettingsRepository
{
    protected $accessPermission = 'view media settings';
    protected $editPermission = 'view media settings';
    protected $titles = [
        'media.maxFileSize' => 'Upload max file size',
        'media.stylesCreationStrategy' => 'Media creation strategy'
    ];
    protected $keys = ['media.maxFileSize', 'media.stylesCreationStrategy'];
    protected $units = [
        'media.maxFileSize' => 'Kb'
    ];

    protected function validations(): array
    {
        return [
            'media_maxFileSize' => 'required|integer|min:0|max:'.upload_max_filesize(),
            'media_stylesCreationStrategy' => 'string|in:lazy,eager'
        ];
    }

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
    protected function fields(): array
    {
        return [
            new Integer(
                'media.maxFileSize',
                [
                    'label' => $this->getFieldLabel('media.maxFileSize'),
                    'required' => true,
                    'max' => upload_max_filesize(),
                    'min' => 0
                ]
            ),
            new _List(
                'media.stylesCreationStrategy',
                [
                    'label' => $this->getFieldLabel('media.stylesCreationStrategy'),
                    'items' => [
                        'lazy' => 'Lazy : Create styles when media is requested',
                        'eager' => 'Eager : Create styles when media is created',
                    ],
                    'required' => true
                ]
            )
        ];
    }
}