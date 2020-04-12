<?php 
namespace Pingu\Media\Contracts;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;
use Pingu\Media\Entities\MediaType;
use Symfony\Component\HttpFoundation\StreamedResponse;

interface MediaContract
{
    /**
     * Create a new instance from an uploaded file
     * 
     * @param UploadedFile $file
     * 
     * @return MediaContract
     */
    public static function createFromUploadedFile(UploadedFile $file): MediaContract;

    /**
     * Get this media relative path
     * 
     * @return string
     */
    public function getPath(): string;

    /**
     * Get the disk instance this media is stored in
     * 
     * @return Filesystem
     */
    public function getDisk(): Filesystem;

    /**
     * Get the raw content of this media
     * 
     * @return string
     */
    public function getContent(): string;

    /**
     * Does the file exist on disk
     * 
     * @return bool
     */
    public function fileExists(): bool;

    /**
     * Force download of this media
     * 
     * @param string|null $name
     * @param array       $headers
     * 
     * @return StreamedResponse
     */
    public function download(?string $name = null, array $headers = []): StreamedResponse;
    
    /**
     * Gets this media url, for images a style can be given
     * 
     * @param string|null $style
     * @param array       $fallbacks
     * 
     * @return string
     */
    public function url(): string;

    /**
     * When was the file updated
     * 
     * @return int|string
     */
    public function lastModified(?string $format = null);

    /**
     * Gets this media's size
     * 
     * @return int
     */
    public function size(?string $unit = null): int;

    /**
     * Delete this media's file, and all its styles if it's an image
     */
    public function deleteFile();

    /**
     * Get the extension for that media
     * 
     * @return string
     */
    public function getExtension(): string;

    /**
     * Url for the icon of this media
     * 
     * @return string
     */
    public function urlIcon(): string;
}