<?php

namespace Pingu\Media\Entities;

use Illuminate\Http\File;
use Pingu\Core\Entities\BaseModel;
use Pingu\Media\Entities\Media;
use Pingu\Media\Exceptions\MediaStyleException;

class ImageStyle extends BaseModel
{
	protected $casts = [
		'transformations' => 'json'
	];

	public function images()
	{
		return $this->belongsToMany(Media::class);
	}

	public static function allNames()
	{
		return static::all()->pluck('machineName');
	}

	public function getFolder()
	{
		return str_plural($this->machineName);
	}

	public function getImagePath(Media $media)
	{
		return $media->getFolder().'/'.$this->getFolder().'/'.$media->getName();
	}

	public static function findByName(string $name)
	{
		if(!$style = static::where(['machineName' => $name])->first()){
			throw MediaStyleException::notDefined($name);
		}
		return $style;
	}

	public function deleteImage(Media $media)
	{
		$path = $this->getImagePath($media);
		if($media->getDisk()->exists($path)){
			$media->getDisk()->delete($path);
		}
		$media->styles()->detach($this);
	}

	public function url(Media $media)
	{
		return $media->getDisk()::url($this->getImagePath($media));
	}

	public function moveImage(Media $media)
	{
		$oldName = $media->getFolder().'/'.$this->getFolder().'/'.$media->getOriginal('name').'.'.$media->extension;
		$newName = $this->getImagePath($media);
		$media->getDisk()->move($oldName, $newName);
	}

	public function createImage(Media $media)
	{
		$tmpName = uniqid('image-', true).'.'.$media->extension;
		$tmpFile = temp_path($tmpName);
		\Storage::disk('tmp')->put($tmpName, $media->getContent());
		foreach($this->transformations as $transformation){
			$transformer = new $transformation['class']($transformation['options']);
			$transformer->process($tmpFile);
		}
		$target = $media->getFolder().'/'.$this->getFolder();
		$media->getDisk()->putFileAs($target, new File($tmpFile), $media->getName());
		\Storage::disk('tmp')->delete($tmpName);
		$media->styles()->attach($this);
		return $target.'/'.$media->getName();
	}
}