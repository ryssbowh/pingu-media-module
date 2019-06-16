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

	public function getImagePath(string $name)
	{
		return $this->getFolder().'/'.$name;
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
		$path = $media->media_type->folder.'/'.$this->getImagePath($media->getName());
		$media->getDisk()::delete($path);
		$media->styles()->detach($this);
	}

	public function url(Media $media)
	{
		return $media->getDisk()::url($media->media_type->folder.'/'.$this->getImagePath($media->getName()));
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
		return $target;
	}
}