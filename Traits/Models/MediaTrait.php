<?php

namespace Pingu\Media\Traits\Models;

use Illuminate\Http\File;
use Pingu\Media\Entities\ImageStyle;
use Pingu\Media\Entities\MediaType;

trait MediaTrait
{
	public function styles()
	{
		return $this->belongsToMany(ImageStyle::class);
	}

	public function styleNames()
	{
		return $this->styles->pluck('machineName')->toArray();
	}

	public function getFolder()
	{
		return $this->media_type->folder;
	}

	public function getName()
	{
		return $this->name.'.'.$this->extension;
	}

	public function getPath()
	{
		return $this->getFolder().'/'.$this->getName();
	}

	public function media_type()
    {
        return $this->belongsTo(MediaType::class);
    }

    public function getDisk()
    {
        return \Storage::disk($this->disk);
    }

    public function getContent()
    {
    	return $this->getDisk()->get($this->getPath());
    }

    public function fileExists()
    {
    	return $this->getDisk()->exists($this->getPath());
    }

    public function download(?string $name = null, array $headers = [])
    {
    	return $this->getDisk()->download($this->getpath(), $name, $headers);
    }

	protected function relativeStylePath(string $style)
	{
		$style = ImageStyle::findByName($style);
		return $this->getFolder().'/'.$style->getImagePath($this->getName());
	}

	public function isImage()
	{
		return $this->media_type->machineName == 'image';
	}


	protected function urlStyle(string $style, array $fallbacks = [])
	{
		array_unshift($fallbacks, $style);
		foreach($fallbacks as $styleName){
			if($this->hasStyle($styleName)){
				return $this->getDisk()->url($this->relativeStylePath($styleName));
			}
		}
		return $this->getDisk()->url($this->getPath());
	}

	public function hasStyle(string $name)
	{
		return in_array($name, $this->styleNames());
	}
	

	public function url(?string $style = null, array $fallbacks = [])
	{
		if(!is_null($style) and $this->isImage()){
			return $this->urlStyle($style, $fallbacks);
		}
		return $this->getDisk()::url($this->getPath());
	}

	public function size()
	{
		return $this->size;
	}

	public function createStyles()
	{
		if(!$this->isImage()) return;
		$this->deleteStyles();
		foreach($this->styles as $style){
			$style->createImage($this);
		}
	}

	public function deleteStyles()
	{
		if(!$this->isImage()) return;
		foreach($this->styles as $style){
			$style->deleteImage($this);
		}
	}
}