<?php

namespace Pingu\Media\Traits\Models;

use Pingu\Media\Entities\Media;

trait MediaTypeTrait
{
	public static function getByExtension(string $ext)
    {
    	return static::whereJsonContains('extensions', $ext)->first();
    }

    public function media()
    {
    	return $this->hasMany(Media::class);
    }

    public function generateUniqueName(string $name)
    {
    	$media = $this->media->where('name', '=' , $name)->first();
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