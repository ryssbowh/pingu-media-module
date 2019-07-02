<?php

namespace Pingu\Media\Traits\Models;

use Illuminate\Http\File;
use Pingu\Media\Entities\ImageStyle;
use Pingu\Media\Entities\MediaType;
use Pingu\Media\Exceptions\MediaException;

trait MediaTrait
{
	/**
	 * Trait booter. Register events to delete files when media is handled.
	 */
	public static function bootMediaTrait()
	{
		static::updating(function($media){
			if($media->isDirty('name')){
				$test = $media->media_type->hasMediaCalled($media->name, $media);
				if($test){
					throw MediaException::nameExists($media->name);
				}
			}
		});
		static::created(function($media){
			$media->createStyles();
		});
		static::updated(function($media){
			if($media->isDirty('name')){
				$media->renameFile();
			}
		});
		static::deleting(function($media){
			$media->deleteFile();
		});
	}

	/**
	 * Media type relation
	 * 
	 * @return BelongsToMany
	 */
	public function media_type()
    {
        return $this->belongsTo(MediaType::class);
    }

	/**
	 * Styles relation
	 * 
	 * @return BelongsToMany
	 */
	public function styles()
	{
		return $this->belongsToMany(ImageStyle::class);
	}

	/**
	 * Get all styles names this media has files for
	 * 
	 * @return array
	 */
	public function styleNames()
	{
		return $this->styles->pluck('machineName')->toArray();
	}

	/**
	 * Get this media base folder
	 * 
	 * @return string
	 */
	public function getFolder()
	{
		return $this->media_type->folder;
	}

	/**
	 * Get this media name (including extension)
	 * 
	 * @return string
	 */
	public function getName()
	{
		return $this->name.'.'.$this->extension;
	}

	/**
	 * Get this media path
	 * 
	 * @return string
	 */
	public function getPath()
	{
		return $this->getFolder().'/'.$this->getName();
	}

	/**
	 * Get the disk instance this media is stored in
	 * 
	 * @return Filesystem
	 */
    public function getDisk()
    {
        return \Storage::disk($this->disk);
    }

    /**
     * Get the raw content of this media
     * 
     * @return string
     */
    public function getContent()
    {
    	return $this->getDisk()->get($this->getPath());
    }

    /**
     * Does the file exist on disk
     * 
     * @return bool
     */
    public function fileExists()
    {
    	return $this->getDisk()->exists($this->getPath());
    }

    /**
     * Force download of this media
     * 
     * @param  string|null $name
     * @param  array       $headers
     * @return StreamedResponse
     */
    public function download(?string $name = null, array $headers = [])
    {
    	return $this->getDisk()->download($this->getpath(), $name, $headers);
    }

    /**
     * Get the path for a style
     * 
     * @param  string $style
     * @return string
     */
	protected function stylePath(string $style)
	{
		$style = ImageStyle::findByName($style);
		return $style->getImagePath($this);
	}

	/**
	 * Is this media an image
	 * 
	 * @return boolean
	 */
	public function isImage()
	{
		return $this->media_type->machineName == 'image';
	}

	/**
	 * Returns the url for a style for this media
	 * will look in the styles fallbacks array if this media doesn't have that style
	 * 
	 * @param  string $style
	 * @param  array  $fallbacks
	 * @return string
	 */
	protected function urlStyle(string $style, array $fallbacks = [])
	{
		array_unshift($fallbacks, $style);
		foreach($fallbacks as $styleName){
			if($this->hasStyle($styleName)){
				return $this->getDisk()->url($this->stylePath($styleName));
			}
		}
		return $this->getDisk()->url($this->getPath());
	}

	/**
	 * Does this media has this style name
	 * 
	 * @param  string  $name
	 * @return boolean
	 */
	public function hasStyle(string $name)
	{
		return in_array($name, $this->styleNames());
	}
	
	/**
	 * Gets this media url, for images a style can be given
	 * 
	 * @param  string|null $style
	 * @param  array       $fallbacks
	 * @return string
	 */
	public function url(?string $style = null, array $fallbacks = [])
	{
		if(!is_null($style) and $this->isImage()){
			return $this->urlStyle($style, $fallbacks);
		}
		return $this->getDisk()::url($this->getPath());
	}

	/**
	 * Gets this media's size
	 * 
	 * @return integer
	 */
	public function size()
	{
		return $this->size;
	}

	/**
	 * Creates the styles files for this media (images only)
	 */
	public function createStyles()
	{
		if(!$this->isImage()) return;
		ImageStyle::all()->each(function($style){
			$style->createImage($this);
		});
	}

	/**
	 * Rename this media, and all its styles if it's an image
	 */
	public function renameFile()
	{
		$this->renameStyles();
		$oldName = $this->getFolder().'/'.$this->getOriginal('name').'.'.$this->extension;
		$this->getDisk()->move($oldName, $this->getPath());
	}

	/**
	 * Rename all styles for this media (images only)
	 */
	protected function renameStyles()
	{
		if(!$this->isImage()) return;
		$this->styles->each(function($style){
			$style->moveImage($this);
		});
	}

	/**
	 * Delete this media's file, and all its styles if it's an image
	 */
	public function deleteFile()
	{
		$this->deleteStyles();
		$this->getDisk()->delete($this->getPath());
	}

	/**
	 * Deletes the styles files for this media (images only)
	 */
	protected function deleteStyles()
	{
		if(!$this->isImage()) return;
		$this->styles->each(function($style){
			$style->deleteImage($this);
		});
	}
}