<?php

namespace Pingu\Media\Entities;

use Illuminate\Http\UploadedFile;
use Pingu\Entity\Contracts\BundleContract;
use Pingu\Entity\Support\BundledEntity;
use Pingu\Media\Bundles\FileBundle;
use Pingu\Media\Contracts\MediaContract;
use Pingu\Media\Traits\IsMedia;

class File extends BundledEntity implements MediaContract
{
    use IsMedia;

    public $timestamps = false;

    protected $touches = ['media'];

    /**
     * @inheritDoc
     */
    public static function createFromUploadedFile(UploadedFile $file): MediaContract
    {
        return static::create();
    }

    /**
     * @inheritDoc
     */
    public function urlIcon(): string
    {
        return $this->media_type->urlIcon();
    }

    /**
     * @inheritDoc
     */
    public function bundleClass(): string
    {
        return FileBundle::class;
    }

    /**
     * @inheritDoc
     */
    public function bundleInstance(): ?BundleContract
    {
        return new FileBundle;
    }
}
