<?php

namespace Pingu\Media\Validation;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\UploadedFile;

class MediaRules
{
    /**
     * Rule that checks if an uploaded file is a defined extension
     *  
     * @param string $attribute 
     * @param UploadedFile $file
     * @param array $extensions
     * @param Validator $validator
     * 
     * @return bool
     */
    public function fileExtension($attribute, $file, $extensions, $validator)
    {
        $ext = $file->guessExtension();
        if (!in_array($ext, $extensions)) {
            $validator->setCustomMessages(
                [
                $attribute.'.file_extension' => 'Extension is not valid, valid types are ('.implode(', ', $extensions).')'
                ]
            );
            return false;
        }
        return true;
    }

    /**
     * Checks if an array of extensions is not already in use in other media types
     * 
     * @param string $attribute
     * @param array $extensions
     * @param array $ids
     * @param Validator $validator
     * 
     * @return bool
     */
    public function uniqueExtensions($attribute, $extensions, $ids, $validator)
    {
        $ignore = null;
        if (isset($ids[0])) {
            $ignore = \MediaType::getById($ids[0]);
        }
        $defined = \Media::getAvailableFileExtensions($ignore);
        $duplicates = [];
        $extensions = array_map(
            function ($ext) {
                return trim($ext);
            }, explode(',', trim($extensions, ', '))
        );
        foreach ($extensions as $ext) {
            if (in_array($ext, $defined)) {
                $duplicates[] = $ext;
            }
        }
        if ($duplicates) {
            $validator->setCustomMessages(
                [
                $attribute.'.unique_extensions' => 'Extensions '.implode(',', $duplicates).' are already defined in other media types'
                ]
            );
            return false;
        }
        return true;
    }

    /**
     * Checks if a name is unique for a media
     * 
     * @param string $attribute
     * @param string $name      
     * @param array $ids       
     * @param Validator $validator 
     * 
     * @return bool
     */
    public function uniqueMediaName($attribute, $name, $ids, $validator) {
        $media = MediaModel::findOrFail($ids[0]);
        if($media->name == $name) { 
            return true;
        }
        return !$media->media_type->hasMediaCalled($name, $media);
    }
}