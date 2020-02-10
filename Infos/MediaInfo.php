<?php 

namespace Pingu\Media\Infos;

use Pingu\Info\Support\InfoProvider;

class MediaInfo extends InfoProvider
{
    /**
     * @inheritDoc
     */
    public static function slug(): string
    {
        return 'media.infos';
    }

    /**
     * @inheritDoc
     */
    public static function title(): string
    {
        return 'Media';
    }

    /**
     * @inheritDoc
     */
    public function infos(): array
    {   
        return [
            'Files on disk' => $this->countFilesOnDisk(),
            'Total size on disk' => $this->countSizeOnDisk()
        ];
    }

    protected function countFilesOnDisk()
    {
        $disk = \Storage::disk('public');
        $files = count($disk->allFiles(config('media.folder')));
        $original = count($disk->files(config('media.folder')));
        return $original. ' files, '.($files - $original).' styles';
    }

    protected function countSizeOnDisk()
    {
        $disk = \Storage::disk('public');
        $size = 0;
        foreach ($disk->files(config('media.folder')) as $file) {
            $size += $disk->size($file);
        }
        return friendly_size($size);
    }

    /**
     * @inheritDoc
     */
    public function permission(): string
    {
        return 'view medias infos';
    }
}