<?php

namespace Pingu\Media\Traits\Models;

use Pingu\Media\Entities\Media;

trait MediaTypeTrait
{
    public static function bootMediaTypeTrait()
    {
        static::creating(function($model){
            $model->folder = $model->machineName;
        });
    }

	public static function getByExtension(string $ext)
    {
    	return static::whereJsonContains('extensions', $ext)->first();
    }

    public function medias()
    {
    	return $this->hasMany(Media::class);
    }

    public function hasMediaCalled(string $name, ?Media $ignore = null)
    {
        $media = $this->medias->where('name', '=', $name)->first();
        if(!$media) return false;
        if(!is_null($ignore) and $media == $ignore) return false;
        return true;
    }

    public function generateUniqueName(string $name)
    {
    	$media = $this->medias->where('name', '=' , $name)->first();
    	if($media){
    		$elems = explode('-', $name);
    		if(sizeof($elems) > 1 and is_numeric($elems[sizeof($elems)-1])){
    			$number = $elems[sizeof($elems)-1] + 1;
    			unset($elems[sizeof($elems)-1]);
    			$elems[] = $number;
    			$name = implode('-', $elems);
    		}
    		else{
    			$name .= '-1';
    		}
    		return $this->generateUniqueName($name);
    	}
    	return $name;
    }
}