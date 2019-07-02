<?php

namespace Pingu\Media;

use Illuminate\Database\QueryException;
use Illuminate\Http\UploadedFile;
use Pingu\Media\Entities\ImageStyle;
use Pingu\Media\Entities\Media as MediaModel;
use Pingu\Media\Entities\MediaType;
use Pingu\Media\Exceptions\MediaTypeException;

class Media
{
	public function getDefaultDisk()
	{
		return config('media.defaultDisk');
	}

	public function getMediaTypeForExtension(string $extension)
	{
		$type = MediaType::getByExtension($extension);
		if(is_null($type)){
			throw MediaTypeException::extensionNotDefined($extension);
		}
		return $type;
	}

	public function uploadFile(UploadedFile $file, ?string $disk = null, $styles = 'all')
	{
		if(is_null($disk)){
			$disk = $this->getDefaultDisk();
		}
		$diskInstance = \Storage::disk($disk);
		$ext = $file->guessExtension();
		$type = $this->getMediaTypeForExtension($ext);
		$name = rtrim($file->getClientOriginalName(), '.'.$ext);
		$name = $type->generateUniqueName($name);
		try{
			$diskInstance->putFileAs($type->folder, $file, $name.'.'.$ext);
			$media = new MediaModel([
				'name' => $name,
				'extension' => $ext,
				'disk' => $disk,
				'size' => $file->getSize(),
			]);
			$media->media_type()->associate($type);
			$media->save();
		}
		catch(QueryException $e){
			$diskInstance->delete($type->folder.'/'.$name.'.'.$ext);
			throw $e;
		}
		return $media;
	}

	public function getAvailableFileExtensions(?MediaType $ignore = null)
	{
		$out = [];
		foreach(MediaType::all() as $media){
			if(!is_null($ignore) and $media == $ignore) continue;
			$out = array_merge($out, $media->extensions);
		}
		return $out;
	}
}